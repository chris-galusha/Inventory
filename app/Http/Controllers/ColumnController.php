<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Column;
use Exception;
use Artisan;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ColumnStoreRequest;
use App\Http\Requests\ColumnUpdateRequest;


class ColumnController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index () {

    $columns = getColumns();

    return view('admin.column.index', compact('columns'));

  }

  public function confirmDelete(Column $column) {
    return view('admin.column.delete', compact('column'));
  }

  public function edit (Column $column) {
    $types = getTypes();
    return view('admin.column.edit', compact('column', 'types'));

  }

  public function show (Column $column) {
    return view('admin.column.show', compact('column'));
  }

  public function update (ColumnUpdateRequest $request, Column $column) {

    $parameters = $request->all();

    $display = $parameters['display'] ?? null;
    $protected = $parameters['protected'] ?? null;
    $required = $parameters['required'] ?? null;

    if ($display) {
      $column->display = true;
    } else {
      $column->display = false;
    }

    if ($protected) {
      $column->protected = true;
    } else {
      $column->protected = false;
    }

    if ($required) {
      $column->required = true;
    } else {
      $column->required = false;
    }

    $column->display_name = $parameters['display-name'] ?? null;

    if ($column->type->name == 'dropdown') {
      $value_names = $parameters['value-names'] ?? null;
      removeAllValues($column);
      if ($value_names) {
        foreach ($value_names as $value_name) {
          $value = getOrCreateValueByName($value_name);
          $column->values()->attach($value);
        }
      }
    }

    $column->save();

    $message = Auth::user()->name." updated a column: $column->display_name.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect("/columns/$column->id");

  }

  public function changeType (Column $column) {
    $request = request();
    $parameters = $request->all();

    $convert_to_dropdown = $parameters['convert-to-dropdown'] ?? null;
    $convert_to_string = $parameters['convert-to-string'] ?? null;

    if ($convert_to_string !== null) {
      $string = getTypeByName('string');
      $column->type_id = $string->id;
      removeAllValues($column);
      $column->save();

    } elseif ($convert_to_dropdown !== null) {
      $dropdown = getTypeByName('dropdown');
      $column->type_id = $dropdown->id;
      $column->save();
    }

    $message = Auth::user()->name." changed a column's type: $column->display_name.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect("/columns/$column->id");
  }

  public function create () {
    $types = getTypes();
    return view('admin.column.create', compact('types'));
  }

  public function store (ColumnStoreRequest $request) {
    $parameters = $request->all();


    $column_name = $parameters['column-name'] ?? null;

    if (Column::where('name', $column_name)->exists()) {
      $error = 'A column with the name '.$column_name.' already exists.';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $type_id = $parameters['type-id'] ?? null;
    $type = getTypeByID($type_id);

    $display = $parameters['display'] ?? null;
    $protected = $parameters['protected'] ?? null;
    $required = $parameters['required'] ?? null;
    $allow_delete = $parameters['allow-delete'] ?? true;

    $display_name = $parameters['display-name'] ?? null;

    $column = new Column();

    try {
      $column->display = $display ? $display : false;
      $column->protected = $protected ? $protected : false;
      $column->required = $required ? $required : false;
      $column->name = $column_name;
      $column->display_name = $display_name ? $display_name : $column_name;
      $column->type_id = $type->id;
      $column->allow_delete = $allow_delete;

      $column->save();

      $value_names = $parameters['value-names'] ?? null;
      if ($value_names !== null && $type->name == 'dropdown') {
        foreach ($value_names as $value_name) {
          $value = getOrCreateValueByName($value_name);
          $column->values()->attach($value);
        }

        $column->save();
      }


    $sql_type = $type->sql_type;

    Schema::table('items', function (Blueprint $table) use ($sql_type, $column_name, $type) {
      if ($type->name == 'float') {
        $table->$sql_type($column_name, 8, 2)->nullable();
      } else {
        $table->$sql_type($column_name)->nullable();
      }
    });

    Artisan::call('migrate');

    $message = Auth::user()->name." created a column: $column->display_name.";
    Log::debug($message);
    session()->flash('message', $message);

  } catch (Exception $e) {
    $column->delete();
    dd($e);
  }

    return redirect('/columns');
  }

  public function destroy (Column $column) {

    if (!$column->allow_delete) {
      $error = 'Column cannot be deleted';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $this->dropColumnFromItems($column);
    removeAllValues($column);
    $column->delete();

    $message = Auth::user()->name." deleted a column: $column->display_name.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect('/columns');

  }

  public function dropColumnFromItems (Column $column) {
    if (Schema::hasColumn('items', $column->name)) {

      Schema::table('items', function (Blueprint $table) use ($column) {

        $table->dropColumn($column->name);

      });

    }
  }
}
