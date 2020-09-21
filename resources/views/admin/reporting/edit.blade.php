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

      <form class="form" action="/reports/{{ $report->id }}" method="post">
        @csrf
        @method('PATCH')

        <div class="field">
          <label class="label">
            Title:
            <input class='input' type="text" required name="columns[name]" value="{{ collect(old('columns'))->get('name') ?? $report->name }}">
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Description:
            <textarea class='textarea' required name="columns[description]" placeholder="Description...">{{ collect(old('columns'))->get('description') ?? $report->description }}</textarea>
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
              <select class="" required name="columns[frequency]">
                @foreach ($frequencies as $frequency)
                  <option {{ old('frequency') == $frequency["value"] ? "selected" : ($report->frequency == $frequency["value"] ? "selected" : "") }} value="{{ $frequency["value"] }}">{{ $frequency["display"] }}</option>
                @endforeach
              </select>
            </div>

          </label>
        </div>

        <div class="field">
          <label class='label'>
            Run at Time (if applicable):
            <input type="time" class='input' name="columns[time]" value="{{ collect(old('columns'))->get('time') ?? $report->time }}">
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Run on Day of Week (if applicable):
            <div class="select">
              <select class="" name="columns[day_of_week]">
                <option {{ $report->day_of_week == 0 ? 'selected' : '' }} value="0">Sunday</option>
                <option {{ $report->day_of_week == 1 ? 'selected' : '' }} value="1">Monday</option>
                <option {{ $report->day_of_week == 2 ? 'selected' : '' }} value="2">Tuesday</option>
                <option {{ $report->day_of_week == 3 ? 'selected' : '' }} value="3">Wednesday</option>
                <option {{ $report->day_of_week == 4 ? 'selected' : '' }} value="4">Thursday</option>
                <option {{ $report->day_of_week == 5 ? 'selected' : '' }} value="5">Friday</option>
                <option {{ $report->day_of_week == 6 ? 'selected' : '' }} value="6">Saturday</option>
              </select>
            </div>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Run on Day of Month (if applicable):
            <input type="number" class='input' name="columns[day_of_month]" value="{{ collect(old('columns'))->get('day-of-month') ?? $report->day_of_month }}" min='1' max='31'>
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Email To:
            <input type="text" class='input' name="columns[email]" value="{{ collect(old('columns'))->get('email') ??$report->email }}" placeholder="Email...">
          </label>
        </div>

        <div class="field">
          <label class='label'>
            Active:
            <input type="checkbox" {{ collect(old('columns'))->get('active') ?? $report->active ? 'checked' : ''}} name="columns[active]" value="1">
          </label>
        </div>

        <div class="buttons">
          <a href="/reports/{{ $report->id }}" class='button is-link'>Back</a>
          <button type="submit" class='button is-primary'>Update</button>
        </div>
      </form>

    </div>

    <form class="form box" action="/reports/{{ $report->id }}" method="post">
      @csrf
      @method('DELETE')
      <button type="submit" class='button is-danger'>Delete</button>
    </form>
    
  </div>
</div>



@endsection('content')
