<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use DateTime;
use Illuminate\Support\Facades\Log;
use Auth;

class ImportController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function import () {
    $columns = getColumns();

    return view('item/import/upload', compact('columns'));
  }

  public function map () {

    $request = request();
    $parameters = $request->all();

    $options = $parameters['options'] ?? null;
    $file = $request->file('file');
    $file_name = $file->store('imports');

    if (!$options) {
      $error = 'No options provided';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $delimiter = $options['delimiter'] ?? null ;
    $include_header = $options['include-header'] ?? null;

    if (!$delimiter) {
      $error = 'Please specify a delimeter';
      return back()->withErrors($error)->withInput(request()->all());
    }
    if (!$include_header) {
      $error = 'Please specify if the header is included';
      return back()->withErrors($error)->withInput(request()->all());
    }

    if (($handle = fopen($file, 'r')) !== FALSE) {

      $csv_header = fgetcsv($handle, 0, $delimiter);
      $db_columns = $this->mapFEColumns($csv_header);

      fclose($handle);
    }

    session()->put('import-file', ['file-name' => $file_name, 'options' => $options, 'csv-header' => $csv_header]);

    $columns = getColumns();

    return view('item.import.map', compact('columns', 'db_columns', 'csv_header'));
  }

  public function mapFEColumns ($csv_header) {
    $csv_to_db = [
      "AssetsAsset_Acquisitiondate" => 'date_acquired',
      "AssetsAsset_Assetnum" => 'fe_id',
      "AssetsAsset_AssetID" => 'inventory_number',
      "AssetsAsset_Department" => 'department',
      "AssetsAsset_DateAdded" => 'created_at',
      "AssetsAsset_DateChanged" => 'updated_at',
      "AssetsAsset_Description" => 'description',
      "AssetsAsset_Disposalmethod" => 'status',
      "AssetsAsset_Location" => 'location',
      "AssetsAsset_Model" => 'model_number',
      "AssetsAsset_Serial" => 'serial_number',
      "AssetsAsset_Vendorname" => 'manufacturer',
      "AssetsAsset_Disposaldate" => 'date_decommissioned',
      "AssetsAsset_Dateinservice" => 'date_in_service'
    ];

    $db_columns = [];

    foreach ($csv_header as $index => $column) {
      $db_column = $csv_to_db[$column] ?? null;
      if ($db_column) {
        $db_columns[$index] = $db_column;
      } else {
        $db_columns[$index] = null;
      }
    }

    return $db_columns;
  }

  public function upload () {

    $request = request();
    $parameters = $request->all();

    $import_file = session()->get('import-file');
    session()->forget('import-file');

    $file_name = $import_file['file-name'] ?? null;
    $csv_header = $import_file['csv-header'] ?? null;
    $options = $import_file['options'] ?? null;

    $update_existing = $parameters['update-existing'] ?? null;

    if (!$file_name) {
      $error = 'File was not transferred properly.';
      return redirect('/import')->withErrors($error)->withInput(request()->all());
    }
    $file = storage_path('app/'.$file_name);
    $csv_column_map = $parameters['csv-column-map'] ?? null;

    if (!$csv_column_map) {
      $error = 'Something went wrong, no csv column map.';
      return redirect('/import')->withErrors($error)->withInput(request()->all());
    }

    if (!$csv_header) {
      $error = 'CSV Header was not transferred properly.';
      return redirect('/import')->withErrors($error)->withInput(request()->all());
    }

    if (!$options) {
      $error = 'Options were not transferred properly.';
      return redirect('/import')->withErrors($error)->withInput(request()->all());
    }

    $import_columns = [];
    $order_csv_column_map = array_flip($csv_header);
    foreach ($csv_column_map as $csv_column => $db_column) {
      if ($db_column == 'null') {
        continue;
      }
      $order = $order_csv_column_map[$csv_column];

      if ($import_columns[$db_column] ?? null !== null) {
        $error = 'Please do not assign one column to multiple headers.';
        return redirect('/import')->withErrors($error)->withInput(request()->all());
      }

      $import_columns[$db_column] = $order;
    }

    asort($import_columns);
    $import_columns = array_flip($import_columns);

    $delimiter = $options['delimiter'] ?? null;

    if (!$delimiter) {
      $error = 'Please specify a delimeter';
      return redirect('/import')->withErrors($error)->withInput(request()->all());
    }

    if (($handle = fopen($file, 'r')) !== FALSE) {
      if ($options['include-header'] ?? null) {
        fgetcsv($handle, 0, $delimiter);
      }

      $count = 0;

      while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {

        $item = new Item();

        foreach ($data as $index => $datum) {

          $column = $import_columns[$index] ?? null;

          if ($column === null) {
            continue;
          }

          if (DateTime::createFromFormat('m/d/Y', $datum) !== FALSE) {
            $date = date_create($datum);
            $datum = date_format($date, 'Y-m-d');
          }

          if (DateTime::createFromFormat('m/d/Y H:i', $datum) !== FALSE) {
            $date = date_create($datum);
            $datum = date_format($date, 'Y-m-d H:i:s');
          }

          if (DateTime::createFromFormat('m/d/Y h:i:s A', $datum) !== FALSE || DateTime::createFromFormat('m/d/Y H:i:s A', $datum) !== FALSE) {
            $date = date_create($datum);
            $datum = date_format($date, 'Y-m-d H:i:s');
          }

          if ($datum == '') {
            $datum = null;
          }

          if ($column == 'inventory_number' && !is_numeric($datum)) {
            $datum = null;
          }

          $item->$column = $datum;
        }

        $max_inventory_number = 1000000;
        if ($item->inventory_number >= $max_inventory_number) {
          Log::debug("Item with inventory number larger than $max_inventory_number: ".$item->inventory_number.", ".$item->description);
          continue;
        }

        if ($item->created_at === null) {
          $item->created_at = nowDateTime();
        }

        if ($item->updated_at === null) {
          $item->updated_at = nowDateTime();
        }

        if (!ensureUniqueItem($item)) {
          Log::debug("Duplicate Item Detected: ".$item->inventory_number.", ".$item->serial_number.", ".$item->fe_id);
          switch ($update_existing) {
            case 'update':
              $count += 1;
              updateItemIfExists($item);
              break;

            case 'overwrite':
              $count += 1;
              overwriteItemIfExists($item);

            case 'replace':
              $count += 1;
              replaceItemIfExists($item);

            default:
              break;
          }
        } else {
          $count += 1;
          $item->save();
        }

      }
      fclose($handle);
    }

    $message = Auth::user()->name." imported $count items.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect('/items');
  }
}
