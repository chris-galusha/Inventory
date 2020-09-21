<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use App\Item;
use App\Column;
use DateTime;
use App\Type;
use App\Value;
use Illuminate\Support\Facades\Log;
use Auth;

class InventoryController extends Controller
{

  /**
  * Create a new controller instance.
  *
  * @return void
  */

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Contracts\Support\Renderable
  */

    public function confirmInventory () {
      $request = request();
      $parameters = $request->all();

      $items = session()->get('items', null);

      if ($items !== null && $items->count() != 0) {
        $total = $items->count();
      } else {
        return view('item/inventory/upload');
      }

      return view('item/inventory/confirm', compact('total'));
    }

    public function inventory () {

      $items = session()->get('items', null);
      if ($items === null || $items->count() == 0) {
        $error = 'No items to inventory';
        return back()->withErrors($error)->withInput(request()->all());
      }

      $location = $parameters['location'] ?? null;

      if ($location == 'not specified') {
        $location = null;
      }

      foreach ($items as $item) {
        $item->update(['last_inventoried' => date('Y-m-d'), 'location' => $location]);
      }

      $message = Auth::user()->name." inventoried ".$items->count()." items.";
      Log::debug($message);
      session()->flash('message', $message);
      session()->forget('items');

      return redirect('/items');
    }

    public function inventoryUpload () {
      return view('item/inventory/upload');
    }

    public function inventoryUploadUpdate () {
      $request = request();
      $parameters = $request->all();
      $file = $request->file('file');
      if ($file == null) {
        return view('item/inventory/upload')->withErrors('No file found!');
      }
      if (($handle = fopen($file, "r")) !== FALSE) {
        $query = Item::query();
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
          $inventory_number = $data[0];
          if (Item::where('inventory_number', $inventory_number)->exists()) {
            $query->orWhere('inventory_number', $inventory_number);
          } else {
            Log::debug('Attempted to inventory unknown item with inventory number '.$inventory_number);
          }
        }
        fclose($handle);
      }

      $items = $query->get();

      session()->put('items', $items);

      return redirect('/inventory/confirm');
    }
  }
