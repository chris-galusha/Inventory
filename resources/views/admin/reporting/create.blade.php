@extends('layouts/layout')

@section('title', 'NUF Inventory New Report')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        New Report
      @endsection

      @include('snippets/banner')

      <div class="box">


      <form class="form" action="/reports" method="post">
        @csrf

        <div class="field">
          <label class='label'>
            Name:
            <input type="text" class='input' name="name" value="{{ old('name') }}" placeholder="Report Name..." required>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Description:
            <textarea class='textarea' name="description" rows="8" cols="80" placeholder="Description..." required>{{ old('description') }}</textarea>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Frequency:
            <div class="select">
              @php
                $frequencies = [
                  ['value' => 'everyMinute', 'display' => 'Every Minute'],
                  ['value' => 'everyFiveMinutes', 'display' => 'Every Five Minutes'],
                  ['value' => 'everyTenMinutes', 'display' => 'Every Ten Minutes'],
                  ['value' => 'everyFifteenMinutes', 'display' => 'EveryFifteenMinutes'],
                  ['value' => 'everyThirtyMinutes', 'display' => 'Every Thirty Minutes'],
                  ['value' => 'hourly', 'display' => 'Every Hour'],
                  ['value' => 'daily', 'display' => 'Every Day'],
                  ['value' => 'dailyAt', 'display' => 'Every Day at Specified Time'],
                  ['value' => 'weekly', 'display' => 'Every Week'],
                  ['value' => 'weeklyOn', 'display' => 'Every Week on Specified Day and Time'],
                  ['value' => 'monthly', 'display' => 'Every Month'],
                  ['value' => 'monthlyOn', 'display' => 'Every Month on Specified Day and Time'],
                  ['value' => 'quarterly', 'display' => 'Every Quarter'],
                  ['value' => 'yearly', 'display' => 'Every Year']
                ];
              @endphp
              <select class="" required name="frequency">
                @foreach ($frequencies as $frequency)
                  <option {{ old('frequency') == $frequency["value"] ? "selected" : ""}} value="{{ $frequency["value"] }}">{{ $frequency["display"] }}</option>
                @endforeach
              </select>
            </div>

          </label>
        </div>

        <div class="field">
          <label class='label'>
            Run at Time (if applicable):
            <input type="time" class='input' name="time" value="12:00" required>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Run on Day of Week (if applicable):
            <div class="select">
              <select class="" name="day-of-week" required>
                <option value="0" selected>Sunday</option>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
              </select>
            </div>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Run on Day of Month (if applicable):
            <input type="number" class='input' name="day-of-month" value="1" min='1' max='31' required>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Active:
            <input type="checkbox" name="active" value="1" checked>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Email report to:
            <input type="text" class='input' name="email" value="email@example.com" placeholder="Email..." required>
          </label>
        </div>

        <div class="buttons">
          <a href="/" class='button is-link'>Back</a>
          <button type="submit" class='button is-success'>Create Report</button>
        </div>

      </form>

    </div>
  </div>
  </div>



@endsection('content')
