@extends('layouts/layout')

@section('title', 'UNF Inventory Mass Update')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        Mass Update {{ $item_count }} Items
      @endsection

      @include('snippets/banner')

      <div class="box">

        <form class="form" action="/items/mass/update" method="post">
          @csrf
          @method('PATCH')

          <div class="field">
            <div class="control">
              <button type="button" class='button is-info is-outlined' name="select-all">Select All Fields</button>
              <button type="button" class="button is-info is-outlined" name="clear-all">Clear Selection</button>
            </div>
          </div>

          <div class="box">
            <h3>Displayed Columns</h3>
            @foreach ($displayed_columns as $column)
              @if (!$column->protected)
                <div class="field">
                  <label class="label checkbox-label">
                    <input type='checkbox' class='select-item' name='enable-columns[{{ $column->name }}]' value="1"/>
                    {{ $column->display_name }}
                  </label>
                  <div class="control">
                    @if ($column->type->name == 'dropdown')
                      <div class="select">
                        <select class=' {{ $errors->has($column->name) ? 'is-danger' : '' }}' name='columns[{{ $column->name }}]' >
                          <option {{ collect(old('columns'))->get($column->name) === null ? 'selected' : ''}} value="">Not Specified</option>
                          @foreach ($column->values as $value)
                            <option {{ collect(old('columns'))->get($column->name) == $value->name ? 'selected' : ''}} value="{{ $value->name }}">{{ $value->name }}</option>
                          @endforeach
                        </select>
                      </div>

                    @else
                      @if ($column->type->name == 'textarea')
                        <textarea name="columns[{{ $column->name }}]" class="textarea {{ $errors->has($column->name) ? 'is-danger' : '' }}" placeholder="{{ $column->display_name }}">{{ collect(old('columns'))->get($column->name) }}</textarea>
                      @else
                        @if ($column->type->name == 'boolean')
                          <input type="checkbox" name="columns[{{ $column->name }}]" class="boolean {{ $errors->has($column->name) ? 'is-danger' : '' }}" value="1" {{ collect(old('columns'))->get($column->name) == "1" ? 'checked' : ''}}>
                        @else
                          <input type="{{ $column->type->html_type }}" {{ $column->type->name == 'float' ? 'step=any' : '' }} class='input {{ $errors->has($column->name) ? 'is-danger' : '' }}' name="columns[{{ $column->name }}]" placeholder="{{ $column->display_name }}" value='{{ collect(old('columns'))->get($column->name) }}' >

                        @endif
                      @endif

                    @endif
                  </div>
                </div>
              @endif
            @endforeach
          </div>

          <div class="box">
            <h3>Hidden Columns</h3>
            @foreach ($hidden_columns as $column)
              @if (!$column->protected)
                <div class="field">
                  <label class="label checkbox-label">
                    <input type='checkbox' class='select-item' name='enable-columns[{{ $column->name }}]' value="1"/>
                    {{ $column->display_name }}
                  </label>
                  <div class="control">
                    @if ($column->type->name == 'dropdown')
                      <div class="select">
                        <select class=' {{ $errors->has($column->name) ? 'is-danger' : '' }}' name='columns[{{ $column->name }}]' >
                          <option {{ collect(old('columns'))->get($column->name) === null ? 'selected' : ''}} value="">Not Specified</option>
                          @foreach ($column->values as $value)
                            <option {{ collect(old('columns'))->get($column->name) == $value->name ? 'selected' : ''}} value="{{ $value->name }}">{{ $value->name }}</option>
                          @endforeach
                        </select>
                      </div>

                    @else
                      @if ($column->type->name == 'textarea')
                        <textarea name="columns[{{ $column->name }}]" class="textarea {{ $errors->has($column->name) ? 'is-danger' : '' }}" placeholder="{{ $column->display_name }}">{{ collect(old('columns'))->get($column->name) }}</textarea>
                      @else
                        @if ($column->type->name == 'boolean')
                          <input type="checkbox" name="columns[{{ $column->name }}]" class="boolean {{ $errors->has($column->name) ? 'is-danger' : '' }}" value="1" {{ collect(old('columns'))->get($column->name) == "1" ? 'checked' : ''}}>
                        @else
                          <input type="{{ $column->type->html_type }}" {{ $column->type->name == 'float' ? 'step=any' : '' }} class='input {{ $errors->has($column->name) ? 'is-danger' : '' }}' name="columns[{{ $column->name }}]" placeholder="{{ $column->display_name }}" value='{{ collect(old('columns'))->get($column->name) }}' >

                        @endif
                      @endif

                    @endif
                  </div>
                </div>
              @endif
            @endforeach
          </div>

          <div class="field">
            <div class="control">
              <a class="button is-link" href="/items">Back</a>
              <button type="submit" class="button is-purple">Update</button>
            </div>
          </div>


        </form>
      </div>
    </div>
  </div>



@endsection('content')
