@extends('layouts/layout')

@section('title', 'Item Importing')

@section('content')

  <div class="container import">
    <div class="content">

      @section('banner-title')
        Import Items
      @endsection

      @include('snippets/banner')

      <div class="box">
        <form class="form" action="/import/upload" method="post">
          @csrf
          <h4>Map columns from CSV to columns in the database.</h4>
          <div class="scrolling-table-60 box">
          <table class='table is-hoverable is-fullwidth'>
            <tbody>
              @php
              $counter = 1;
              @endphp
              @foreach ($csv_header as $index => $csv_column)
                <tr>
                  <td style="width: 1px">
                    <label>{{ $counter++.'.' }}</label>
                  </td>
                  <td style="width: 1px">
                    <label>{{ $csv_column }}</label>
                  </td>
                  <td style="width: 1px">
                    <i class='fa fa-angle-double-right'></i>
                  </td>
                  <td>
                    <div class="control">
                      <select class="" name="csv-column-map[{{ $csv_column }}]">
                        <option {{ $db_columns[$index] == null ? 'selected' : '' }} value="null">Ignore</option>
                        @if ($db_columns)
                          @foreach ($columns as $column)
                            <option {{ $column->name == $db_columns[$index] ? 'selected' : '' }} value="{{ $column->name }}">{{ $column->display_name }}</option>
                          @endforeach
                        @else
                          @foreach ($columns as $column)
                            <option value="{{ $column->name }}">{{ $column->display_name }}</option>
                          @endforeach
                        @endif

                      </select>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          </div>
          <div class="field">
            <h3>When duplicate records are encountered:</h3>
            <label class='label'>
              <input type="radio" name="update-existing" value="0" checked="checked">
              Keep Existing Records
            </label>
            <label class='label'>
              <input type="radio" name="update-existing" value="update">
              Update Existing Records (All fields will be updated, but not emptied)
            </label>
            <label class='label'>
              <input type="radio" name="update-existing" value="overwrite">
              Overwrite Existing Records (All fields will be updated)
            </label>
            <label class='label'>
              <input type="radio" name="update-existing" value="replace">
              Replace Existing Records (Old item is deleted)
            </label>
          </div>

          <div class='control'>
            <a href="/import" class='button is-link'>Back</a>
            <button class='button is-primary' type="submit">Import</button>
          </div>
        </form>
      </div>
    </div>
  </div>



@endsection('content')
