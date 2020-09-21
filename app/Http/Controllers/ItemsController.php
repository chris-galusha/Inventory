<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Log;
use Auth;
use Redirect;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Requests\ItemStoreRequest;

class ItemsController extends Controller
{

  public function __construct()
  {
    $this->middleware(['auth', 'normal'])->except([
      'index',
      'show'
    ]);

    $this->middleware('admin')->only('destroy');
  }

  public function index()
  {
    $request = request();
    $parameters = $request->all();

    // Log::debug($parameters);

    $options = $parameters['options'] ?? null;
    $options = $this->cleanOptions($options);
    $new_search = $options['new-search'] ?? null;

    $query_builder = session()->get('query-builder', null);

    if ($query_builder !== null && !$new_search) {
      $query = generateAdvancedQuery($query_builder);
    } else {
      session()->forget('query-builder');
      $query = $this->generateQuery($options);
    }

    $statistics = getQueryStatistics($query);

    $item_ids = $parameters['item-ids'] ?? null;
    if ($item_ids !== null) {
      $items = getItemsByID($item_ids);
    } else {
      $items = $query->get();
    }

    session()->put('items', $items);

    $action = $parameters['action'] ?? null;
      switch ($action) {
        case 'export':
        return redirect('/export');

        case 'inventory':
        return redirect('/inventory/confirm');

        case 'update':
        return redirect('/items/mass/mass-edit');

        case 'delete':
        return redirect('/items/mass/delete/confirm');

        case 'decommission':
        return redirect('/decommission/confirm');

        case 'recommission':
        return redirect('/decommission/recommission/confirm');

        case 'reporting':
        return redirect('/reports');

        case 'select':
        return redirect('/items/select/upload');

        default:
        break;
      }

      $items = $this->getPaginatedSortedItemsFromQuery($options, $query);

      $columns = getDisplayedColumns();

      return view('index', compact('items', 'options', 'statistics', 'columns'));
    }

    public function cleanOptions ($options) {
      $sort_by = $options['sort-by'] ?? null;
      $sort_direction = $options['sort-direction'] ?? null;
      $paginate_count = $options['paginate-count'] ?? null;
      $new_search = $options['new-search'] ?? null;
      $case_sensitive = $options['case-sensitive'] ?? null;
      $include_hidden_columns = $options['include-hidden-columns'] ?? null;
      $show_decommissioned = $options['show-decommissioned'] ?? null;
      $q = $options['q'] ?? null;
      $filter = $options['filter'] ?? null;

      // Ensure a value exists for each index
      $options['q'] = $q;
      $options['filter'] = $filter;
      $options['case-sensitive'] = $case_sensitive;
      $options['include-hidden-columns'] = $include_hidden_columns;
      $options['show-decommissioned'] = $show_decommissioned;

      if ($sort_direction === null || $new_search == '1') {
        $options['sort-direction'] = 'ASC';
      }

      if ($sort_by === null || !columnExists($sort_by) || $new_search == '1') {
        $options['sort-by'] = 'description';
      }

      if ($paginate_count === null) {
        $options['paginate-count'] = 15;
      }

      return $options;

    }

    public function selectUpload () {
      return view('item.select');
    }

    public function selectByCSV () {
      $request = request();
      $file = $request->file('file');
      $options = $request->input('options');
      $delimiter = $options['delimiter'] ?? ",";
      $include_decommissioned = $options['include-decommissioned'] ?? "0";

      if ($file == null) {
        return back()->withErrors('No file uploaded!');
      }

      if (($handle = fopen($file, "r")) !== FALSE) {
        $inventory_numbers = [];
        while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
          $inventory_number = $data[0];
          $item = Item::where('inventory_number', $inventory_number);
          if ($item->exists()) {
            $item = $item->first();
            $is_decommissioned = !($item->date_decommissioned == null);
            if ($include_decommissioned || !$is_decommissioned) {
              array_push($inventory_numbers, $inventory_number);
            }
          } else {
            Log::debug('Attempted to select unknown item with inventory number '.$inventory_number);
          }
        }
        fclose($handle);
      }

      $inventory_numbers = array_unique($inventory_numbers);

      # Build custom query builder to pass to index method
      $query_builder = ['columns' => ['inventory_number' => ["contains" => "1", "search_modifier" => "none"]]];
      $options = ['show-decommissioned' => $include_decommissioned];
      $query_builder['options'] = $options;
      $bound_counter = 0;
      foreach ($inventory_numbers as $inventory_number) {
        $bound_counter++;
        $query_builder['columns']['inventory_number']['bounds']["bound-$bound_counter"]['lower-bound'] = $inventory_number;
        $query_builder['columns']['inventory_number']['bounds']["bound-$bound_counter"]['upper-bound'] = $inventory_number;
      }

      session()->put('query-builder', $query_builder);

      $item_count = sizeof($inventory_numbers);

      $message = Auth::user()->name." selected $item_count item(s).";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect('/items');
    }

    public function getPaginatedSortedItemsFromQuery ($options, $query) {

      $sort_by = $options['sort-by'] ?? null;
      $sort_direction = $options['sort-direction'] ?? null;
      $paginate_count = $options['paginate-count'] ?? null;

      $items = $query->orderBy($sort_by, $sort_direction)->paginate($paginate_count);

      return $items;
    }

    public function generateQuery ($options) {
      $q = $options['q'] ?? null;
      $filter = $options['filter'] ?? null;
      $case_sensitive = $options['case-sensitive'] ?? null;
      $include_hidden_columns = $options['include-hidden-columns'] ?? null;
      $show_decommissioned = $options['show-decommissioned'] ?? null;

      if ($case_sensitive) {
        $search_comparison_operator = 'like binary';
      } else {
        $search_comparison_operator = 'like';
      }

      $query = Item::query();

      if ($q !== null && $q != '') {
        if ($filter && $filter != 'all') {
          $query->where($filter, $search_comparison_operator, "%$q%");
        } else {
          $columns = $include_hidden_columns ? getColumns() : getDisplayedColumns();

          $query->where( function ($query) use ($columns, $search_comparison_operator, $q) {
            foreach($columns as $column){
              $query->orWhere($column->name, $search_comparison_operator, "%$q%");
            }
          });
        }
      }

      if (!$show_decommissioned) {
        $query = hideDecommissioned($query);
      }

      return $query;
    }

    public function create () {
      $columns = getColumns();
      return view('item/create', compact('columns'));
    }

    public function edit (Item $item) {
      $displayed_columns = getDisplayedColumns();
      $hidden_columns = getHiddenColumns();
      return view('item/edit', compact('hidden_columns', 'displayed_columns', 'item'));
    }

    public function show (Item $item) {
      $columns = getColumns();
      return view('item/show', compact('columns', 'item'));
    }

    public function store (ItemStoreRequest $request) {
      $parameters = $request->all();

      $columns = $parameters['columns'] ?? null;
      $serial_number = $columns['serial_number'] ?? null;
      $inventory_number = $columns['inventory_number'] ?? null;
      $fe_id = $columns['fe_id'] ?? null;
      $create_count = $parameters['create-count'] ?? null;

      if ($create_count !== null && intval($create_count) > 1) {
        $create_count = intval($create_count);
        $item_count = $create_count;
        while ($create_count > 0) {
          if (ensureNullNumbers([$serial_number, $fe_id])) {
            if (ensureUniqueInventory($columns['inventory_number'])) {
              $item = Item::create($columns);
              $columns['inventory_number'] = $columns['inventory_number'] + 1;
            } else {
              $error = 'One or more inventory numbers are not unique: '.$columns['inventory_number'];
              return back()->withErrors($error)->withInput(request()->all());
            }
          } else {
            $error = 'Do not specify a serial number or fe id when creating multiple new items.';
            return back()->withErrors($error)->withInput(request()->all());
          }
          $create_count--;
        }
      } else {
        $item_count = 1;
        if (ensureUniqueNumbers($inventory_number, $serial_number, $fe_id)) {
          $item = Item::create($columns);
        } else {
          $error = 'Duplicate inventory number or serial number detected.';
          return back()->withErrors($error)->withInput(request()->all());
        }
      }

      $message = Auth::user()->name." created $item_count item(s): $item->description.";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect("/items");
    }

    public function update (ItemUpdateRequest $request, Item $item) {
      $parameters = $request->all();

      $serial_number = $parameters['serial_number'] ?? null;
      $inventory_number = $parameters['inventory_number'] ?? null;

      $inventory_number != $item->inventory_number ? $unique_inventory = ensureUniqueInventory($inventory_number) : $unique_inventory = true;
      $serial_number != $item->serial_number ? $unique_serial = ensureUniqueSerial($serial_number) : $unique_serial = true;

      if ($unique_serial && $unique_inventory) {
        $item->update($parameters['columns']);
      } else {
        $error = 'Duplicate inventory number or serial number detected.';
        return back()->withErrors($error)->withInput(request()->all());
      }

      $message = Auth::user()->name." updated an item: $item->description.";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect("/items");
    }

    public function destroy (Item $item) {
      $item->delete();

      $message = Auth::user()->name." deleted an item: $item->description.";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect('/items');
    }

  public function massDelete () {

    $items = session()->get('items', null);
    if ($items === null) {
      $error = 'No Items to delete';
      return back()->withErrors($error)->withInput(request()->all());
    }
    foreach ($items as $item) {
      $item->delete();
    }

    $message = Auth::user()->name." deleted ".$items->count()." items.";
    Log::debug($message);
    session()->flash('message', $message);
    session()->forget('items');

    return redirect("/items");
  }

  public function confirmMassDelete () {
    $request = request();
    $parameters = $request->all();

    $items = session()->get('items', null);

    if ($items === null || $items->count() == 0) {
      $error = 'No Items to delete';
      return back()->withErrors($error)->withInput(request()->all());
    } else {
      $total = $items->count();

    }

    return view('/item/confirmDelete', compact('total'));
  }

  public function massEdit () {

    $request = request();
    $parameters = $request->all();

    $items = session()->get('items', null);

    if ($items === null || $items->count() == 0) {
      $error = 'No items provided';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $displayed_columns = getDisplayedColumns();
    $hidden_columns = getHiddenColumns();

    $item_count = $items->count();

    if ($item_count == 1) {
      $item = $items->first();
      return view('item.edit', compact('displayed_columns', 'hidden_columns', 'item'));
    }


    return view('item.massEdit', compact('displayed_columns', 'hidden_columns', 'item_count'));
  }

  public function massUpdate(ItemUpdateRequest $request) {
    $parameters = $request->all();

    $items = session()->get('items', null);

    $columns = $parameters['columns'] ?? null;
    $enable_columns = $parameters['enable-columns'] ?? null;

    if ($enable_columns === null) {
      $error = 'Select at least one column to update.';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $columns_to_update = [];
    foreach ($columns as $key => $value) {
      $enable_column = $enable_columns[$key] ?? null;
      if ($enable_column) {
        $columns_to_update[$key] = $value;
      }
    }

    // TODO: Add Validation
    // $this->validateItem($request);

    foreach ($items as $item) {
      $item->update($columns_to_update);
    }

    $message = Auth::user()->name." updated ".$items->count()." items.";
    Log::debug($message);
    session()->flash('message', $message);
    session()->forget('items');

    return redirect('/items');
  }
}
