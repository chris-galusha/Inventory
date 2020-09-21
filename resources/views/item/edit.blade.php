@extends('layouts/layout')

@section('title', 'UNF Inventory Edit')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        Edit {{ $item->description }}
      @endsection

      @include('snippets/banner')

      <div class="box">

        <form class="form" action="/items/{{ $item->id }}" method="post">
          @method('PATCH')
          @csrf

          <div class="box">
            <h3>Displayed Columns</h3>
            @foreach ($displayed_columns as $column)
              @php
              $name = $column->name;
              @endphp
              @if (!$column->protected)
                <div class="field">
                  <label class="label">{{ $column->display_name }}</label>
                  <div class="control">
                    @if ($column->type->name == 'dropdown')
                      <div class="select">
                        <select class=' {{ $errors->has($column->name) ? 'is-danger' : '' }}' name="columns[{{ $column->name }}]" >
                          <option {{ collect(old('columns'))->get($column->name) == '' ? 'selected' : ''}} value="">Not Specified</option>
                          @foreach ($column->values as $value)
                            <option {{ collect(old('columns'))->get($column->name) == $value->name || $item->$name == $value->name ? 'selected' : '' }} value="{{ $value->name }}">{{ $value->name }}</option>
                          @endforeach
                        </select>
                      </div>

                    @else
                      @if ($column->type->name == 'textarea')
                        <textarea name="columns[{{ $column->name }}]" class="textarea {{ $errors->has($column->name) ? 'is-danger' : '' }}" placeholder="{{ $column->display_ame }}">{{ collect(old('columns'))->get($column->name) }}</textarea>
                      @else
                        @if ($column->type->name == 'boolean')
                          <input type="checkbox" name="columns[{{ $column->name }}]" class="boolean {{ $errors->has($column->name) ? 'is-danger' : '' }}" value="1" {{ collect(old('columns'))->get($column->name) ? (collect(old('columns'))->get($column->name) == "1" ? 'checked' : '' ) : ($item->$name == "1" ? 'checked' : '')}}>
                        @else
                          <input type="{{ $column->type->html_type }}" {{ $column->type->name == 'float' ? 'step=any' : '' }} class="input {{ $errors->has($column->name) ? 'is-danger' : '' }}" name="columns[{{ $column->name }}]" placeholder="{{ $column->display_name }}" value='{{ collect(old('columns'))->get($column->name) ? collect(old('columns'))->get($column->name) : $item->$name }}' {{ $column->required ? "required" : "" }} >
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
              @php
              $name = $column->name;
              @endphp
              @if (!$column->protected)
                <div class="field">
                  <label class="label">{{ $column->display_name }}</label>
                  <div class="control">
                    @if ($column->type->name == 'dropdown')
                      <div class="select">
                        <select class=' {{ $errors->has($column->name) ? 'is-danger' : '' }}' name="columns[{{ $column->name }}]" >
                          <option {{ collect(old('columns'))->get($column->name) == '' ? 'selected' : ''}} value="">Not Specified</option>
                          @foreach ($column->values as $value)
                            <option {{ collect(old('columns'))->get($column->name) == $value->name || $item->$name == $value->name ? 'selected' : '' }} value="{{ $value->name }}">{{ $value->name }}</option>
                          @endforeach
                        </select>
                      </div>

                    @else
                      @if ($column->type->name == 'textarea')
                        <textarea name="columns[{{ $column->name }}]" class="textarea {{ $errors->has($column->name) ? 'is-danger' : '' }}" placeholder="{{ $column->display_ame }}">{{ collect(old('columns'))->get($column->name) ?? $item->$name }}</textarea>
                      @else
                        @if ($column->type->name == 'boolean')
                          <input type="checkbox" name="columns[{{ $column->name }}]" class="boolean {{ $errors->has($column->name) ? 'is-danger' : '' }}" value="1" {{ collect(old('columns'))->get($column->name) ? (collect(old('columns'))->get($column->name) == "1" ? 'checked' : '' ) : ($item->$name == "1" ? 'checked' : '')}}>
                        @else
                          <input type="{{ $column->type->html_type }}" {{ $column->type->name == 'float' ? 'step=any' : '' }} class="input {{ $errors->has($column->name) ? 'is-danger' : '' }}" name="columns[{{ $column->name }}]" placeholder="{{ $column->display_name }}" value='{{ collect(old('columns'))->get($column->name) ? collect(old('columns'))->get($column->name) : $item->$name }}' {{ $column->required ? "required" : "" }} >
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
              <button type="submit" class="button is-primary">Update</button>
            </div>
          </div>



        </form>
      </div>
    </div>
  </div>



@endsection('content')
