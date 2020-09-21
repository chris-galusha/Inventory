@extends('layouts/layout')

@section('title', 'NUF Inventory Create Columns')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        Create New Column
      @endsection

      @include('snippets/banner')

      <div class="box">

        <form class="form create-column" action="/columns" method="post">
          @csrf
          <input type="hidden" name="allow-delete" value="1">

          <div class="field">
            <label class='label'>
              Display on front page:
              <input type="checkbox" name="display" value="1" checked>
            </label>
          </div>
          <div class="field">
            <label class='label'>
              Column database name:
              <input type="text" class='input' name="column-name" value="{{ old('column-name') }}" placeholder="Name of Column (lowercase and underscores only)" required>
            </label>
          </div>
          <div class="field">
            <label class='label'>
              Display name:
              <input type="text" class='input' name="display-name" value="{{ old('display-name') }}" placeholder="Display Name" required>
            </label>
          </div>
          <div class="field">
            <label class='label'>
              Data Type:
              <div class="select">
                <select name="type-id" required>
                  @foreach ($types as $type)
                    @if (!$type->protected)
                      <option value="{{ $type->id }}">{{ $type->display_name }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </label>
          </div>
          <div class="field box">
            <label class="label">Add Values (for dropdowns only):</label>
            <div class="values">

            </div>
            <button type="button" class='button is-success add-value'>Add Value</button>
          </div>
          <div class="field">
            <label class='label'>
              Protected from editing:
              <input type="checkbox" name="protected" value="1">
            </label>
          </div>
          <div class="field">
            <label class='label'>
              Required:
              <input type="checkbox" name="required" value="1">
            </label>
          </div>
          <a class='button is-link' href="/columns">Back</a>
          <button type="submit" class='button is-primary'>Create New Column</button>
        </form>
      </div>
    </div>
  </div>



@endsection('content')
