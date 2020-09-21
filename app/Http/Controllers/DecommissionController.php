<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Item;
use Auth;
use Storage;

class DecommissionController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function recommission () {

    $items = session()->get('items', null);
    if ($items !== null) {
      foreach ($items as $item) {
        $item->update(['status' => 'Not Specified', 'date_decommissioned' => null, 'reason_for_decommission' => null]);
      }
    } else {
      $error = 'No items provided';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $message = Auth::user()->name." recommissioned ".$items->count()." items.";
    Log::debug($message);
    session()->flash('message', $message);
    session()->forget('items');

    return redirect("/items");
  }

  public function confirmRecommission () {
    $request = request();
    $parameters = $request->all();

    $items = session()->get('items', null);

    if ($items === null || $items->count() == 0) {
      $error = 'No items provided';
      return back()->withErrors($error)->withInput(request()->all());
    } else {
      $total = $items->count();

      return view('item/decommission/confirmRecommission', compact('total'));
    }
  }

  public function confirmDecommission () {

    $request = request();
    $parameters = $request->all();

    $items = session()->get('items', null);

    if ($items === null || $items->count() == 0) {
      return view('item/decommission/upload');
    }

    $total = $items->count();


    return view('item/decommission/confirmDecommission', compact('total'));
  }

  public function decommission () {
    $request = request();
    $parameters = $request->all();

    $items = session()->get('items', null);

    if ($items === null || $items->count() == 0) {
      $error = 'No items to decommission';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $reason = $parameters['reason'] ?? null;

    if ($reason === null) {
      $reason = 'old, lifecycle';
    }

    $this->decommissionItems($items, $reason);

    $message = Auth::user()->name." decommissioned ".$items->count()." items.";
    Log::debug($message);
    session()->flash('message', $message);
    session()->forget('items');

    return redirect("/items");
  }

  public function decommissionItems($items, $reason) {
    foreach ($items as $item) {
      $item->update(['status' => 'Decommissioned', 'date_decommissioned' => date('Y-m-d'), 'reason_for_decommission' => $reason]);
    }
  }

  public function decommissionUpload () {
    return view('item/decommission/upload');
  }

  public function decommissionUploadUpdate () {

    $request = request();
    $parameters = $request->all();

    $file = $request->file('file');

    if ($file == null) {
      $error = 'No CSV Provided';
      return back()->withErrors($error)->withInput(request()->all());
    }
    if (($handle = fopen($file, "r")) !== FALSE) {
      $items_to_decommission = [];
      while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        array_push($items_to_decommission, $data[0]);
      }
      fclose($handle);
      $query = Item::query();
      foreach ($items_to_decommission as $inventory_number) {
        if (Item::where('inventory_number', $inventory_number)->exists()) {
          $query->orWhere('inventory_number', $inventory_number);
        } else {
          Log::debug('Attempted to decommission an item with inventory number '.$inventory_number.' but could not find an item with that inventory number in the database.');
        }
      }
    }

    $items = $query->get();

    session()->put('items', $items);

    return redirect('/decommission/confirm');
  }

  public function getDecommissionedCSVPath() {
    return storage_path('decommission/('.date('Y-m').') Decommissioned.csv');
  }
}
