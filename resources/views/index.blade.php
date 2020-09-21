@extends('layouts/layout')

@section('title', 'NUF Inventory')

@section('content')

  <div class="container inventory">
    <div class="content">
      @section('banner-title')
        NUF Inventory
      @endsection

      @include('snippets/banner')
      <form class="form" name='inventory-form' action="/items" method="get">
        @csrf

        <input type="hidden" id='new-search' name="options[new-search]" value="0">
        <input type="hidden" id='sort-by' name="options[sort-by]" value="{{ $options['sort-by'] }}">
        <input type="hidden" id='sort-direction' name="options[sort-direction]" value="{{ $options['sort-direction'] }}">

        <div class="inventory-controls">
          <div class="search">

            <div class="search-wrapper">
              <input class="input is-border" id='search' name='options[q]' type="search" placeholder="Search..." value='{{ $options['q'] }}' aria-label="Search">
              <button type="submit" class="button is-link fa fa-search"></button>
            </div>

            <div class="filter">
              <div class="dropdown is-hoverable">
                <div class="dropdown-trigger">
                  <button type='button' class="button is-border" aria-haspopup="true" aria-controls="dropdown-menu">
                    <span><i class="fa fa-filter"></i> Filter</span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                  <div class="dropdown-content control">
                    <div class="dropdown-item">
                      <label class='checkbox'>
                        <input type="checkbox" value='1' name="options[case-sensitive]" {{ $options['case-sensitive'] ? 'checked' : '' }}>
                        Case Sensitive
                      </label>
                    </div>
                    <div class="dropdown-item">
                      <label class='checkbox'>
                        <input type="checkbox" value='1' name="options[show-decommissioned]" {{ $options['show-decommissioned'] ? 'checked' : '' }}>
                        Show Decommisioned
                      </label>
                    </div>
                    <div class="dropdown-item">
                      <label class='checkbox'>
                        <input type="checkbox" value='1' name="options[include-hidden-columns]" {{ $options['include-hidden-columns'] ? 'checked' : '' }}>
                        Include Hidden Fields
                      </label>
                    </div>
                    <hr class="dropdown-divider">
                    <div class="dropdown-item">
                      <label class='radio'>
                        <input type='radio' name='options[filter]' value='all' checked>
                        All Fields
                      </label>
                    </div>

                    @foreach ($columns as $column)
                      <div class="dropdown-item">
                        <label class='radio'>
                          <input type='radio' value='{{ $column->name }}' name='options[filter]' {{ $options['filter'] == "$column->name" ? 'checked' : '' }}>
                          {{ $column->display_name }}
                        </label>
                      </div>
                    @endforeach

                  </div>
                </div>
              </div>
            </div>
            <div class="">
              <a href="/filter" class="button is-border">Advanced Filter</a>
            </div>
            <div class="items-per-page">
              <label>Items Per Page:
                <div class="select">
                  <select class="" id='paginate-count' name="options[paginate-count]">
                    <option {{ $options['paginate-count'] == '15' ? 'selected' : ''}} value="15">15</option>
                    <option {{ $options['paginate-count'] == '25' ? 'selected' : ''}} value="25">25</option>
                    <option {{ $options['paginate-count'] == '50' ? 'selected' : ''}} value="50">50</option>
                    <option {{ $options['paginate-count'] == '75' ? 'selected' : ''}} value="75">75</option>
                    <option {{ $options['paginate-count'] == '100' ? 'selected' : ''}} value="100">100</option>
                  </select>
                </div>
              </label>
            </div>
            <div><button type='submit' class="button is-primary" name='action' value='select'>Select Items by CSV</button></div>
          </div>

          <div class="query-statistics box">
            <p>Selected Items: <span id="selected-count">0</span></p>
            <p>Items Fetched By Query: {{ $statistics['query-count'] }}</p>
            <p>Total Items: {{ $statistics['total-items'] }}</p>
            <p>Deleted Items: {{ $statistics['deleted-items'] }}</p>
            <p>Decommissioned Items: {{ $statistics['decommissioned-items'] }}</p>
          </div>

          <div class="inventory-buttons">
            <div><a href="/import" class="button is-primary" role="button">Import</a></div>
            <div><button type='submit' class="button is-primary" name='action' value='export'>Export</button></div>
            <div><a href="/items/create" class="button is-success" role="button">New Item</a></div>
            <div><button type='submit' class="button is-purple" name='action' value='update'>Update Item</button></div>
            <div><button type='submit' class='button is-info' name='action' value='reporting'>Reporting</button></div>
            <div><button type='submit' class="button is-warning" name='action' value='decommission'>Decommission</button></div>
            <div><button type='submit' class="button is-success" name='action' value='recommission'>Recommission</button></div>
            <div><button type='submit' class="button is-info" name='action' value='inventory'>Inventory</button></div>
            <div><button type='submit' class="button is-danger" name='action' value='delete'>Delete</button></div>
          </div>

          <div class='selection'>
            <button type="button" name="select-all" class='button is-info is-outlined'>Select All Items on Page</button>
            <button type="button" name="clear-all" class='button is-info is-outlined'>Clear Selection</button>
          </div>
        </div>

        <div class='inventory-table'>
          @if (sizeof($items) > 0)

            <div class="table-wrapper box-shadow">
              <table class='table is-bordered is-narrow is-hoverable is-fullwidth'>
                <thead>
                  <tr>
                    <th class='selected'>Select</th>
                    @foreach($columns as $column)
                      <th class='{{ $column->name }}'>
                        {{ $column->display_name }}
                        <span class="sort-buttons">
                          <i class="fa fa-angle-double-up {{ $options['sort-by'] != $column->name || $options['sort-direction'] != 'ASC' ? 'disabled' : '' }}" aria-hidden="true"></i>
                          <i class="fa fa-angle-double-down {{ $options['sort-by'] != $column->name || $options['sort-direction'] != 'DESC' ? 'disabled' : '' }}" aria-hidden="true"></i>
                        </span>
                      </th>
                    @endforeach
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th class='selected'>Select</th>
                    @foreach($columns as $column)
                      <th class='{{ $column->name }}'>{{ $column->display_name }}</th>
                    @endforeach
                  </tr>
                </tfoot>
                <tbody>


                  @foreach($items as $item)

                    <tr class=''>
                      <td class='selected'>
                        <label class="">
                          <input type="checkbox" class="select-item" name='item-ids[]' value="{{ $item->id }}">
                        </label>
                      </td>
                      @foreach ($columns as $column)
                        @php
                        $name = $column->name;
                        @endphp
                        <td class='{{ $column->name }}'>
                          <a href="/items/{{ $item->id }}">
                            {{ $item->$name == '' ? 'N/A' : $item->$name }}
                          </a>
                        </td>
                      @endforeach

                    </tr>
                  @endforeach

                </tbody>


              </table>
            </div>

          @else
            <tr><td><h1>No items were found matching that query.</h1></td></tr>
          @endif

          {{ $items->appends(Request::except('page'))->links() }}

        </form>

      </div>
    </div>
  </div>



@endsection('content')
