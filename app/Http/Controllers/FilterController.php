<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;
use App\Item;
use App\Column;
use DateTime;
use App\Type;
use App\Value;
use Auth;
use Illuminate\Support\Facades\Log;

class FilterController extends Controller
{

  public function filter () {
    $columns = getColumns();
    return view('item/filter', compact('columns'));
  }

  public function advancedFilter() {
    $request = request();
    $parameters = $request->all();

    $action = $parameters['action'] ?? null;

    $query_builder = $parameters['query-builder'] ?? null;

    session()->put('query-builder', $query_builder);

    if ($action == 'filter') {
      return redirect('/items');
    } else if ($action == 'report') {
      return redirect('/reports/create');
    } else {
      $error = 'No action selected';
      return back()->withErrors($error)->withInput(request()->all());
    }
  }

}
