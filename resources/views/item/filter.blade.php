@extends('layouts/layout')

@section('title', 'NUF Inventory')

@section('content')

  <div class="container">
    <div class="content">

      @section('banner-title')
        Advanced Filter
      @endsection

      @include('snippets/banner')

      <div class="box">

        <form class="form filter" action="/filter/advanced" method="post">
          @csrf

          <div class="filter-fields box">
            @foreach ($columns as $column)
              @if (!$column->type->protected || $column->type->protected)
                <div class="field">
                  <label class='label'>
                    <span class='underlined'>{{ $column->display_name }}:</span>

                    @if ($column->type->html_type == 'checkbox')
                      <input class='checkbox' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name }}][value]" value="1">
                    @else
                      @if ($column->type->html_type == 'text' || $column->type->html_type == 'textarea')
                        <input class='input' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name.'][q][]' }}" value="" placeholder="Contains...">

                      @else
                        @if ($column->type->html_type == 'dropdown')
                          <input class='input' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name.'][q][]' }}" value="" placeholder="Contains...">

                        @else
                          @if (in_array($column->type->html_type, ['date', 'datetime-local', 'timestamp']))
                            <div class="bound-group" id='bound-1'>
                              <input class='input' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name.'][bounds][bound-1][lower-bound]' }}" value="" placeholder="Lower Bound">
                              -
                              <input class='input' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name.'][bounds][bound-1][upper-bound]' }}" value="" placeholder="Upper Bound">
                            </div>
                            <p>OR</p>
                            <div class="days-ago">
                              <input class='input' type="number" name="{{ "query-builder[columns][$column->name][days-ago]" }}" min="0" value="" placeholder="Days Ago Inclusive...">
                            </div>
                          @else
                            <div class="bound-group" id='bound-1'>
                              <input class='input' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name.'][bounds][bound-1][lower-bound]' }}" value="" placeholder="Lower Bound">
                              -
                              <input class='input' type="{{ $column->type->html_type }}" name="query-builder[columns][{{ $column->name.'][bounds][bound-1][upper-bound]' }}" value="" placeholder="Upper Bound">
                            </div>
                          @endif

                        @endif

                      @endif

                    @endif
                    <div class='search-modifiers'>
                      <div>
                        @if (in_array($column->type->html_type, ['text', 'textarea', 'dropdown']))
                          <input type="radio" name="query-builder[columns][{{ $column->name }}][contains]" checked value="1">
                          Contains
                          <input type="radio" name="query-builder[columns][{{ $column->name }}][contains]" value="0">
                          Doesn't Contain
                        @elseif (in_array($column->type->html_type, ['date', 'datetime-local', 'timestamp']))
                          <input type="radio" name="query-builder[columns][{{ $column->name }}][contains]" checked value="1">
                          Between OR Older Than
                          <input type="radio" name="query-builder[columns][{{ $column->name }}][contains]" value="0">
                          Not Between OR Younger Than
                        @else
                          <input type="radio" name="query-builder[columns][{{ $column->name }}][contains]" checked value="1">
                          Between
                          <input type="radio" name="query-builder[columns][{{ $column->name }}][contains]" value="0">
                          Not Between
                        @endif
                      </div>

                      <div>
                        <input type="radio" name="query-builder[columns][{{ $column->name }}][search-modifier]" checked value="none">
                        No Modifier
                        <input type="radio" name="query-builder[columns][{{ $column->name }}][search-modifier]" value="include-null">
                        Include Empty Fields
                        <input type="radio" name="query-builder[columns][{{ $column->name }}][search-modifier]" value="exclude-null">
                        Only Nonempty Fields
                        <input type="radio" name="query-builder[columns][{{ $column->name }}][search-modifier]" value="only-null">
                        Only Empty Fields
                      </div>
                      <div>
                        <input type="checkbox" name="query-builder[columns][{{ $column->name }}][case-sensitive]" value="1">
                        Case Sensitive
                      </div>
                    </div>

                  </label>

                  <div class="add-remove-buttons">
                    <button type="button" class='button is-success' name="add-or">+</button>
                    <button type="button" class='button is-danger' name="remove-or">-</button>
                  </div>
                </div>

                <hr>
              @endif

            @endforeach
          </div>

          <div class="options">
            <div class="field">
              <label class='label'>
                <input type="checkbox" name="query-builder[options][show-decommissioned]" value="1">
                Include Decommissioned
              </label>
            </div>

            <div class="field">
              <label class='label'>
                <input type="checkbox" name="query-builder[options][include-deleted]" value="1">
                Include Deleted
              </label>
            </div>
          </div>

          <div class="buttons">
            <a href="/" class='button is-info'>Back</a>
            <button type="submit" class='button is-primary' name='action' value='filter'>Apply Filter</button>
            <button type="submit" class='button is-success' name='action' value='report'>Create Report</button>
          </div>
        </form>
      </div>
    </div>
  </div>



@endsection('content')
