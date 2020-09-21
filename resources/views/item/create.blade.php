@extends('layouts/layout')

@section('title', 'NUF Inventory Create')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        Create New Item
      @endsection

      @include('snippets/banner')

      <div class="box">

      <form class="form" action="/items" method="post">
        @csrf
        <div class="box scrolling-table-70">
        @foreach ($columns as $column)
          @if (!$column->protected)
            <div class="field">
              <label class="label">{{ $column->display_name }}:</label>
              <div class="control">
                @if ($column->type->name == 'dropdown')
                  <div class="select">
                    <select class=' {{ $errors->has($column->name) ? 'is-danger' : '' }}' name='columns[{{ $column->name }}]' >
                      <option {{ collect(old('columns'))->get($column->name) == '' ? 'selected' : ''}} value="Not Specified">Not Specified</option>
                      @foreach ($column->values as $value)
                        <option {{ collect(old('columns'))->get($column->name) == $value->name ? 'selected' : ''}} value="{{ $value->name }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>

                @else
                  @if ($column->type->name == 'textarea')
                    <textarea name="columns[{{ $column->name }}]" class="textarea {{ $errors->has($column->name) ? 'is-danger' : '' }}" placeholder="{{ $column->display_name }}">{{ collect(old('columns'))->get($column->name) }}</textarea>
                  @else
                    @if ($column->type == 'boolean')
                      <input type="checkbox" name="columns[{{ $column->name }}]" class="boolean {{ $errors->has($column->name) ? 'is-danger' : '' }}" value="1" {{ collect(old('columns'))->get($column->name) ? 'checked' : ''}}>
                    @else
                      <input type="{{ $column->type->html_type }}" {{ $column->type == 'float' ? 'step=any' : '' }} class='input {{ $errors->has($column->name) ? 'is-danger' : '' }}' name="columns[{{$column->name}}]" placeholder="{{ $column->display_name }}" value='{{ collect(old('columns'))->get($column->name) }}' {{ $column->required ? "required" : "" }} >

                    @endif
                  @endif

                @endif
              </div>
            </div>
          @endif
        @endforeach
        </div>

        <div class="field">
          <label class='label'>
            Number of items to create:
            <input type="number" class='input' min='1' name="create-count" value="1" placeholder="Number of items to create">
          </label>
        </div>

        <div class="field">
          <div class="control">
            <a class="button is-link" href="/items">Back</a>
            <button type="submit" class="button is-success">Create Item</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  </div>



@endsection('content')
