@extends('layouts/layout')

@section('title', 'NUF Reporting')

@section('content')

  <div class="container">
    <div class="content">

      @section('banner-title')
        Reports
      @endsection

      @include('snippets/banner')

      <div class="box">

      <div class="reports box">
        @if ($reports->count() == 0)
          <h3>No reports.</h3>
        @else
          <ol>
            @foreach ($reports as $report)
              <li>
                <a href="/reports/{{ $report->id }}">
                  <div class="report {{ $report->active ? 'active' : '' }}">
                    <p>{{ $report->name }}</p>
                  </div>
                </a>
              </li>
              <hr>
            @endforeach
          </ol>
        @endif

      </div>
      <div class="">
        <h3>To create a new report, use the advanced filter</h3>
        <a class='button is-link' href="/items">Back</a>
        <a class='button is-success' href="/filter">Create Report</a>
      </div>
    </div>
    </div>
  </div>

@endsection('content')
