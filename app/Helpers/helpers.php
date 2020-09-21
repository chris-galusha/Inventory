<?php


use App\Column;
use App\Type;
use App\Value;
use App\Item;
use App\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

function numberToDay(int $number) {
  if ($number >= 0 && $number < 7) {
    $days = [
      0 => 'Sunday',
      1 => 'Monday',
      2 => 'Tuesday',
      3 => 'Wednesday',
      4 => 'Thursday',
      5 => 'Friday',
      6 => 'Saturday'
    ];
    return $days[$number];
  }
  return null;
}

function militaryToStandardTime ($military_time) {
  $military_date = date_create($military_time);
  $standard_time = date_format($military_date, "h:i A");
  return $standard_time;
}

function restoreItems (Collection $items) {
  foreach ($items as $item) {
    restoreItem($item);
  }
}

function restoreItem(Item $item) {
  $item->restore();
}

function columnExists (String $potential_column) {
  $columns = getColumns();
  foreach ($columns as $column) {
    if ($column->name == $potential_column) {
      return true;
    }
  }
  return false;
}

function permanentlyDeleteItems(Collection $items) {
  foreach ($items as $item) {
    permanentlyDeleteItem($item);
  }
}

function permanentlyDeleteItem(Item $item) {
  $item->forceDelete();
}

function removeAllValues (Column $column) {
  DB::table('column_value')->where('column_id', $column->id)->delete();
  $values = Value::all();
  foreach ($values as $value) {
    if (!DB::table('column_value')->where('value_id', $value->id)->exists()) {
      $value->delete();
    }
  }
}

function getQueryStatistics($query) {
  $total_items = Item::count();
  $deleted_items = Item::onlyTrashed()->count();
  $query_count = $query->count();
  $decommissioned_items = Item::whereNotNull('date_decommissioned')->count();

  if ($total_items != 0) {
    $query_count = $query_count.' ('.round($query_count/$total_items*100, 2).'% of total)';
  }

  $statistics = [
    'query-count' => $query_count,
    'total-items' => $total_items,
    'deleted-items' => $deleted_items,
    'decommissioned-items' => $decommissioned_items
  ];

  return $statistics;
}

function isOnlyOneAdmin() {
  $roles = User::all()->pluck('role');
  $admins = $roles->where('id', 1)->count();
  if ($admins == 1) {
    return true;
  }
  return false;
}

function haveDifferentRoles(User $user1, User $user2) {
  return $user1->role->id != $user2->role->id;
}

function areSameUser(User $user1, User $user2) {
  return $user1->id == $user2->id;
}

function emailAlreadyInUse($email, User $user) {
  $emails = User::all()->pluck('email');
  return $emails->contains($email) && $email != $user->email;
}

function updateItemIfExists (Item $item) {
  $existing_item_query = getExistingItemQuery($item);
  if ($existing_item_query !== null && $existing_item_query->exists()) {
    $existing_item = $existing_item_query->first();
    $attributes = $item->toArray();
    foreach ($attributes as $column => $value) {
      if ($value === null) {
        unset($attributes[$column]);
      }
    }
    $existing_item->update($attributes);
  }
}

function overwriteItemIfExists (Item $item) {
  $existing_item_query = getExistingItemQuery($item);
  if ($existing_item_query !== null && $existing_item_query->exists()) {
    $existing_item = $existing_item_query->first();
    $existing_item->update($item->toArray());
  }
}

function replaceItemIfExists (Item $item) {
  $existing_item_query = getExistingItemQuery($item);
  if ($existing_item_query !== null && $existing_item_query->exists()) {
    $existing_item = $existing_item_query->first();
    $existing_item->forceDelete();
    $item->save();
  }
}

function getExistingItemQuery (Item $item) {
  $inventory_number = $item->inventory_number;
  $serial_number = $item->serial_number;
  $fe_id = $item->fe_id;
  $existing_item_query = null;
  if ($inventory_number !== null) {
    $existing_item_query = Item::where('inventory_number', $inventory_number);
  } else if ($serial_number !== null) {
    $existing_item_query = Item::where('serial_number', $serial_number);
  } else if ($fe_id !== null) {
    $existing_item_query = Item::where('fe_id', $fe_id);
  }
  return $existing_item_query;
}

function generateAdvancedQuery(Array $query_builder) {

  $query_builder_columns = $query_builder['columns'] ?? null;

  $columns = getColumnsByName(array_keys($query_builder_columns));

  $show_decommissioned = false;
  $include_deleted = false;

  $options = $query_builder['options'] ?? null;

  if ($options !== null) {
    $include_deleted = $options['include-deleted'] ?? null;
    $show_decommissioned = $options['show-decommissioned'] ?? null;
  }

  $query = Item::query();
  if ($query_builder_columns !== null) {
    foreach ($columns as $column) {

      $case_sensitive = $query_builder_columns[$column->name]['case-sensitive'] ?? null;

      if ($case_sensitive) {
        $search_comparison_operator = 'like binary';
      } else {
        $search_comparison_operator = 'like';
      }

      $bounds = $query_builder_columns[$column->name]['bounds'] ?? null;
      $search_modifier = $query_builder_columns[$column->name]['search-modifier'] ?? null;
      $contains = $query_builder_columns[$column->name]['contains'] ?? null;
      $q = $query_builder_columns[$column->name]['q'] ?? null;
      $days_ago = $query_builder_columns[$column->name]['days-ago'] ?? null;

      if (in_array($column->type->html_type, ['text','textarea','dropdown'])) {

        if (!$contains) {
          $search_comparison_operator = 'not '.$search_comparison_operator;
        }

        $query->where(function ($query) use ($column, $q, $search_comparison_operator, $search_modifier) {
          foreach ($q as $string) {
            if ($string !== null) {
              $query->orWhere($column->name, $search_comparison_operator, "%$string%");
            }
          }
          $query = addSearchModifier($column, $search_modifier, $query);
        });

      } elseif ($column->type->isDate() && $days_ago !== null) {
        $query->where(function ($query) use ($column, $contains, $days_ago, $search_modifier) {
          $date = Carbon::today()->subDays($days_ago);
          if ($contains) {
            $query->orWhere($column->name, '<=', $date);
          } else {
            $query->orWhere($column->name, '>=', $date);
          }
          $query = addSearchModifier($column, $search_modifier, $query);
        });

      } else {
        if ($bounds !== null) {
          $query->where(function ($query) use ($column, $bounds, $contains, $search_modifier) {
            foreach ($bounds as $bound) {
              $lower_bound = $bound['lower-bound'] ?? null;
              $upper_bound = $bound['upper-bound'] ?? null;

              if ($lower_bound !== null && $upper_bound !== null) {
                if ($contains) {
                  $query->orWhereBetween($column->name, [$lower_bound, $upper_bound]);
                } else {
                  $query->orWhereNotBetween($column->name, [$lower_bound, $upper_bound]);
                }
              } else if ($lower_bound !== null || $upper_bound !== null) {
                if ($lower_bound === null) {
                  $bound = $upper_bound;
                  $operator = '<=';
                  $not_operator = '>=';
                } else {
                  $bound = $lower_bound;
                  $operator = '>=';
                  $not_operator = '<=';
                }

                if ($contains) {
                  $query->orWhere($column->name, $operator, $bound);
                } else {
                  $query->orWhere($column->name, $not_operator, $bound);
                }
              }
            }
            $query = addSearchModifier($column, $search_modifier, $query);
          });
        }
      }
    }
  }

  if (!$show_decommissioned) {
    $query = hideDecommissioned($query);
  }

  if ($include_deleted) {
    $query->withTrashed();
  }

  // $query->dd();

  return $query;
}


function addSearchModifier (Column $column, $search_modifier, $query) {
  switch ($search_modifier) {
    case 'include-null':
    $query->orWhereNull($column->name);
    break;

    case 'exclude-null':
    $query->whereNotNull($column->name);
    break;

    case 'only-null':
    $query->whereNull($column->name);
    break;

    default:
    break;
  }
  return $query;
}


function nowDateTime () {
  date_default_timezone_set('America/Chicago');
  return date('Y-m-d h:i:s', time());
}

function nowDate () {
  date_default_timezone_set('America/Chicago');
  return date('Y-m-d');
}

function getTrashedItems () {
  return Item::onlyTrashed()->get();
}

function getColumnByID(String $id) {
  return Column::where('id', $id)->first();
}

function getColumnByName(String $name) {
  return Column::where('name', $name)->first();
}

function getColumnsByName(Array $names) {
  $query = Column::query();
  foreach ($names as $name) {
    $query->orWhere('name', $name);
  }
  return $query->get();
}

function getValueByID(String $id) {
  return Value::where('id', $id)->first();
}

function getOrCreateValueByName(String $name) {
  if (Value::where('name', $name)->exists()) {
    $value = Value::where('name', $name)->first();
  } else {
    $value = new Value();
    $value->name = $name;
    $value->save();
  }
  return $value;
}

function getTypeByID(String $id) {
  return Type::where('id', $id)->first();
}

function getTypeByName(String $name) {
  return Type::where('name', $name)->first();
}

function getItemByID(String $id) {
  return Item::where('id', $id)->first();
}

function getItemsByID(Array $ids) {
  $query = Item::query();
  foreach ($ids as $id) {
    $query->orWhere('id', $id);
  }
  return $query->get();
}

function getDeletedItemsByID (Array $ids) {
  $query = Item::withTrashed();
  foreach ($ids as $id) {
    $query->orWhere('id', $id);
  }
  return $query->get();
}

function getDisplayedColumns () {
  return Column::where('display', 1)->get();
}

function getHiddenColumns () {
  return Column::where('display', 0)->get();
}

function getColumns () {
  return Column::get();
}

function getTypes () {
  return Type::get();
}

function getValues () {
  return Value::get();
}

function reportNameIsUnique($name, $report = null) {
  if ($report !== null) {
    if ($report->name != $name && Report::where('name', $name)->exists()) {
      return false;
    }
  } else {
    if (Report::where('name', $name)->exists()) {
      return false;
    }
  }
  return true;
}

function userIsAdmin() {
  return Auth::check() && Auth::user()->isAdmin();
}

function userIsNormalOrBetter() {
  return Auth::check() && Auth::user()->isNormalOrBetter();
}

function userIsNormal() {
  return Auth::check() && Auth::user()->isNormal();
}

function userIsGuest() {
  return Auth::check() && Auth::user()->isGuest();
}

function checkAuth() {
  return Auth::check();
}

function createHeader (Collection $header_columns, string $delimiter = ',', string $encloser = '"') {
  $header = [];
  foreach ($header_columns as $column) {
    array_push($header, $encloser.$column->display_name.$encloser);
  }
  $header = join($delimiter, $header);

  return $header."\n";
}

function createItemsCSV (String $path, Collection $items, Collection $header_columns = null, Array $options = []) {
  if ($header_columns === null) {
    $header_columns = getColumns();
  }

  $delimiter = $options['delimiter'] ?? ',';
  $encloser = $options['encloser'] ?? '"';
  $include_header = $options['include-header'] ?? false;

  $header = createHeader($header_columns, $delimiter, $encloser);

  if ($include_header) {
    File::append($path, $header);
  }

  appendItemsToCSV($path, $items, $header_columns, $options);

}

function appendItemsToCSV(String $path, Collection $items, Collection $header_columns, Array $options) {
  foreach ($items as $item) {
    appendItemToCSV($path, $item, $header_columns, $options);
  }
}

function appendItemToCSV (String $path, Item $item, Collection $header_columns, Array $options) {
  $delimiter = $options['delimiter'] ?? ',';
  $encloser = $options['encloser'] ?? '"';
  $item_columns = [];
  foreach ($header_columns as $column) {
    $column_name = $column->name;
    $value = $item->$column_name;
    // Escape the encloser character if found
    $value = str_replace($encloser, $encloser.$encloser, $value);
    array_push($item_columns, $encloser.$value.$encloser);
  }
  $line = join($delimiter, $item_columns);
  File::append($path, $line."\n");
}

function ensureUniqueNumbers (String $inventory_number = null, String $serial_number = null, String $fe_id = null) {
  return ensureUniqueSerial($serial_number) && ensureUniqueInventory($inventory_number) && ensureUniqueFEID($fe_id);
}

function ensureNullNumbers (Array $numbers) {
  foreach ($numbers as $number) {
    if ($number !== null) {
      return false;
    }
  }
  return true;
}

function ensureUniqueSerial (String $serial_number = null) {

  if ($serial_number !== null) {
    if (Item::where('serial_number', $serial_number)->whereNotNull('serial_number')->withTrashed()->exists()) {
      Log::debug('Duplicate serial number detected: '.$serial_number);
      return false;
    }
  }

  return true;
}

function ensureUniqueInventory (String $inventory_number = null) {
  if (Item::where('inventory_number', $inventory_number)->whereNotNull('inventory_number')->withTrashed()->exists()) {
    Log::debug('Duplicate inventory number detected: '.$inventory_number);
    return false;
  }
  return true;
}

function ensureUniqueFEID (String $fe_id = null) {
  if (Item::where('fe_id', $fe_id)->whereNotNull('fe_id')->withTrashed()->exists()) {
    Log::debug('Duplicate FE ID detected: '.$fe_id);
    return false;
  }
  return true;
}

function ensureUniqueItem (Item $item) {
  return ensureUniqueNumbers($item->inventory_number, $item->serial_number, $item->fe_id);
}

function validateItem ($request) {

  $columns = getColumns();
  $validate_array = generateValidateArray($columns);
  $request->validate($validate_array);
  //dd($validate_array, $request);
}

function generateValidateArray(Collection $columns, bool $storing = true) {
  $validate_array = [];
  foreach ($columns as $column) {
    if (!$column->protected) {
      $validate_subarray = [];
      if ($column->required && $storing) {
        array_push($validate_subarray, 'required');
      } else {
        array_push($validate_subarray, 'nullable');
      }
      if ($column->type->name == 'ipAddress') {
        array_push($validate_subarray, 'ip');
      }
      if (in_array($column->type->name, ['integer'])) {
        array_push($validate_subarray, 'integer');
        array_push($validate_subarray, 'min:0');
      }
      if (in_array($column->type->name, ['date', 'dateTime'])) {
        array_push($validate_subarray, 'date_format:Y-m-d');
        array_push($validate_subarray, 'date');
      }
      if ($column->name == 'description') {
        array_push($validate_subarray, 'min:3');
      }
      if (in_array($column->type->name, ['string', 'dropdown'])) {
        array_push($validate_subarray, 'string');
        array_push($validate_subarray, 'max:255');
      }
      if (in_array($column->type->name, ['textarea'])) {
        array_push($validate_subarray, 'string');
      }
      if ($column->type->name == 'float') {
        array_push($validate_subarray, 'numeric');
      }
      if ($column->type->name == 'boolean') {
        array_push($validate_subarray, 'boolean');
      }

      $validate_array['columns.'.$column->name] = $validate_subarray;
    }
  }

  return $validate_array;
}

function hideDecommissioned($query) {
  $query->whereNull('date_decommissioned');
  return $query;
}
