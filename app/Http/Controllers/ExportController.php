<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Item;
use Illuminate\Support\Facades\Log;
use Auth;

class ExportController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function export()
  {
    $request = request();
    $parameters = $request->all();

    $columns = getColumns();

    $items = session()->get('items', null);

    if ($items !== null && $items->count() != 0) {
      $total = $items->count();

    } else {
      $error = 'No items provided';
      return back()->withErrors($error)->withInput(request()->all());
    }

    return view('item/export', compact('columns', 'total'));

  }

  public function download () {
    $request = request();
    $parameters = $request->all();

    $options = $parameters['options'] ?? null;
    $export_columns = $parameters['export-columns'] ?? null;

    if ($export_columns === null) {
      $error = 'No Columns selected to export.';
      return back()->withErrors($error)->withInput(request()->all());
    }

    if ($options === null) {
      $error = 'No options were provided.';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $items = session()->get('items', null);

    if ($items === null) {
      $query = Item::query()->withTrashed();
      $items = $query->get();
    }

    $path = storage_path('exports/'.date('d-m-Y h:i:s').'-'.uniqid().'.csv');

    $header_columns = getColumnsByName($export_columns);

    createItemsCSV ($path, $items, $header_columns, $options);

    $save_copy = $options['save-copy'] ?? false;

    $headers = array(
      'Content-Type: text/csv',
      'Location: /',
    );

    $message = Auth::user()->name." exported ".$items->count()." items.";
    Log::debug($message);
    session()->flash('message', $message);
    session()->forget('items');

    return response()->download($path, 'export.csv', $headers)->deleteFileAfterSend(!$save_copy);
  }

}
