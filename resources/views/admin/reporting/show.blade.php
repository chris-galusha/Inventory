@extends('layouts/layout')

@section('title', 'NUF Reporting')

@section('content')

  <div class="container">
    <div class="content">

      @section('banner-title')
        Report: {{ $report->name }}
      @endsection

      @include('snippets/banner')

      <div class="box">

        <div class="">
          <ul>
            <li><b>Description:</b> {{ $report->description }}</li>
            <li><b>Frequency:</b> {{ $report->frequency }}</li>
            <li><b>*Run at Time:</b> {{ militaryToStandardTime($report->time) }}</li>
            <li><b>*Day of Week:</b> {{ numberToDay($report->day_of_week) }}</li>
            <li><b>*Day of Month:</b> {{ $report->day_of_month }}</li>
            <li><b>Email To:</b> {{ $report->email }}</li>
            <li><b>Active:</b> {{ $report->active ? 'Yes' : 'No' }}</li>
            <li><b>(* if applicable)</b></li>
          </ul>
        </div>

        <div class="buttons">
          <a href="/reports" class='button is-link'>Back</a>
          <a class='button is-purple' href='/reports/{{ $report->id }}/edit'>Edit</a>
          <a class='button is-primary' href='/reports/{{ $report->id }}/select'>Select</a>
          <a class='button is-info' href='/reports/run/{{ $report->id }}'>Run Report</a>
        </div>
      </div>
    </div>
  </div>



@endsection('content')
