<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReportStoreRequest;
use App\Http\Requests\ReportUpdateRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use App\Item;
use App\Column;
use DateTime;
use App\Type;
use App\Value;
use App\Report;
use App\Mail\ReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Auth;
use Exception;

class ReportController extends Controller
{

  public function index () {
    $reports = Report::all();
    return view('/admin/reporting/report', compact('reports'));
  }

  public function create () {
    return view('admin/reporting/create');
  }

  public function store (ReportStoreRequest $request) {
    $parameters = $request->all();

    $query_builder = session()->get('query-builder', null);

    if ($query_builder === null) {
      $error = 'No Query was found for report: '.$report->name;
      return back()->withErrors($error)->withInput(request()->all());
    }

    $name = $parameters['name'];
    $description = $parameters['description'];
    $active = $parameters['active'] ?? null;
    $frequency = $parameters['frequency'];
    $email = $parameters['email'];
    $time = $parameters['time'] ?? null;
    $day_of_week = $parameters['day-of-week'] ?? null;
    $day_of_month = $parameters['day-of-month'] ?? null;

    $report = new Report();
    $report->query_builder = $query_builder;
    $report->name = $name;
    $report->description = $description;
    $report->frequency = $frequency;
    $report->email = $email;
    $report->active = $active !== null ? $active : 0;
    $report->time = $time;
    $report->day_of_week = $day_of_week;
    $report->day_of_month = $day_of_month;
    $report->save();

    $message = Auth::user()->name." created a report: $report->name.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect('/reports');
  }

  public function runReport(Report $report) {
    $query_builder = $report->query_builder;
    $options = $query_builder['options'] ?? null;
    $query = generateAdvancedQuery($query_builder, $options);
    $items = $query->get();
    if ($items->count() > 0) {
      $report_path = $this->compileReport($items, $report);
    } else {
      $report_path = null;
    }
    $this->emailReport($report_path, $report, $items);
    Log::debug('Report Ran: '.$report->name);
  }

  public function emailReport ($report_path, $report, $items) {
    try{
      Mail::to($report->email)->send(new ReportMail($report, $report_path, $items));
    } catch (Exception $e) {
      $message = "Emailing report failed. Check email credentials.";
      Log::debug($message."\n$e");
      session()->flash('message', $message);
      return;
    }
    $message = "Report '$report->name' Ran.";
    session()->flash('message', "Report '$report->name' Ran.");
  }

  public function manualRun(Report $report) {
    $this->runReport($report);
    $message = Auth::user()->name." ran a report: $report->name.";
    Log::debug($message);
    return redirect('/reports');
  }

  public function selectFromReport(Report $report) {
    $query_builder = $report->query_builder;
    session()->put("query-builder", $query_builder);
    $message = "Report: $report->name selected";
    session()->flash('message', $message);
    return redirect('/items');
  }

  public function compileReport (Collection $items, Report $report) {
    $report_path = storage_path('reports/'.date('d-m-Y h:i:s').'_'.$report->name.'.csv');
    createItemsCSV($report_path, $items, null, $options = ['include-header' => true]);
    return $report_path;
  }

  public function show (Report $report) {
    return view('admin/reporting/show', compact('report'));
  }

  public function edit (Report $report) {
    return view('admin/reporting/edit', compact('report'));
  }

  public function update (ReportUpdateRequest $request, Report $report) {
    $parameters = $request->all();

    $columns = $parameters['columns'] ?? null;
    if ($columns) {
      $active = $columns['active'] ?? null;
      if ($active === null) {
        $parameters['columns']['active'] = false;
      }
    }

    if (!reportNameIsUnique($columns['name'], $report)) {
      $error = 'A report with that name already exists.';
      return back()->withErrors($error)->withInput(request()->all());
    }

    $report->update($parameters['columns']);

    $message = Auth::user()->name." updated a report: $report->name.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect("/reports/$report->id");
  }

  public function destroy (Report $report) {
    $report->delete();

    $message = Auth::user()->name." deleted a report: $report->name.";
    Log::debug($message);
    session()->flash('message', $message);

    return redirect('/reports');
  }
}
