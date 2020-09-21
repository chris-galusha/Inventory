@extends('layouts/layout')

@section('title', 'Item Exporting')

@section('content')

  <div class="container">
    <div class="content">

      @section('banner-title')
        Exporting {{ $total ?? "" }} Items
      @endsection

      @include('snippets/banner')

      <div class="box">
        <form class="form" action="/export/download" method="post">
          @csrf

          <div class="field">
            <div class="control">
              <button type="button" class='button is-info is-outlined' name="select-all">Select All Fields</button>
              <button type="button" class="button is-info is-outlined" name="clear-all">Clear Selection</button>
            </div>
          </div>
          <div class="scrolling-table-55 box">
          @foreach ($columns as $column)
            <div class="field">
              <label class='checkbox'>
                <div class="control">
                  <input type='checkbox' class='select-item' name='export-columns[]' value='{{ $column->name }}' checked/>
                </div>
                {{$column->display_name}}
              </label>
            </div>
          @endforeach
          </div>

          <div class="options">
            <div class="field">
              <label>
                Delimiter:
                <div class="control">
                  <input type="text" name="options[delimiter]" value=",">
                </div>
              </label>
            </div>
            <div class="field">
              <label>
                Fields enclosed by:
                <div class="control">
                  <input type="text" name="options[encloser]" value="&quot">
                </div>
              </label>
            </div>
            <div class="field">
              <label class='checkbox'>
                <div class="control">
                  <input type="checkbox" name='options[include-header]' value='1' checked/>
                </div>
                Include Header
              </label>
            </div>

            <div class="field">
              <label class='checkbox'>
                <div class="control">
                  <input type="checkbox" name='options[save-copy]' value='1'/>
                </div>
                Save Copy to Server
              </label>
            </div>
          </div>

          <div class="buttons">
            <a href="/items" class='button is-link'>Back</a>
            <button type="submit" class='button is-primary'>Export</button>
          </div>
        </form>
      </div>
    </div>
  </div>



@endsection('content')
