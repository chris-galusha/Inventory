@extends('layouts/layout')

@section('title', 'NUF Inventory Edit')

@section('content')
  <div class="container">
    <div class="content">

        @section('banner-title')
          Edit {{ $column->display_name." ($column->name)" }}
        @endsection

        @include('snippets/banner')

      <div class="box">

        <h4>Type: {{ $column->type->display_name }}</h4>

        <form class="form box edit-column" action="/columns/{{ $column->id }}" method="post">
          @csrf
          @method('PATCH')

          @if ($column->type->name == 'dropdown')
            <div class="field">
              <button type="submit" class='button is-warning' onclick='this.form.action="/columns/{{ $column->id }}/changeType"' name="convert-to-string" value='1'>Change Type to Text</button>
            </div>
          @endif

          @if ($column->type->name == 'string' )
            <div class="field">
              <button type="submit" class='button is-warning' onclick='this.form.action="/columns/{{ $column->id }}/changeType"' name="convert-to-dropdown" value='1'>Change Type to Dropdown</button>
            </div>
          @endif

          <div class="field">
            <label class='label'>
              Display on front page:
              <input value='1' type='checkbox' name='display' {{ $column->display ? 'checked' : '' }}>
            </label>
          </div>
          <div class="field">
            <label class="label">
              Display name:
              <input type="text" class='input' name="display-name" value="{{ $column->display_name }}" placeholder="Display Name" required>
            </label>
          </div>
          <div class="field">
            <label class="label">
              Protected from editing:
              <input type="checkbox" name="protected" value="1" {{ $column->protected ? 'checked' : '' }}>
            </label>
          </div>
          <div class="field">
            <label class="label">
              Required:
              <input type="checkbox" name="required" value="1" {{ $column->required ? 'checked' : '' }}>
            </label>
          </div>

          @if ($column->type->name == 'dropdown')
            <div class="field box">
              <label class="label">Edit Values:</label>
              <div class="values">

                @foreach ($column->values as $value)
                  <div class="value">
                    <input type="text" class='input' name="value-names[]" value="{{ $value->name }}" placeholder="New Value..." required>
                    <button type="button" class='button is-danger remove-value'>Remove Value</button>
                  </div>
                @endforeach

              </div>
              <button type="button" class='button is-success add-value'>Add Value</button>
            </div>
          @endif

          <div class="buttons">
            <button type="submit" class='button is-purple'>Update Column</button>
          </div>
        </form>
        <form class="form box" style="margin-top: 1em" action="/columns/{{ $column->id }}/confirm-delete" method="get">
          @csrf
          <button type="submit" class='button is-danger'>Delete Column</button>
        </form>
        <div class="buttons">
          <a class='button is-link' href="/columns/{{ $column->id }}">Back</a>
        </div>
      </div>
    </div>
  </div>



@endsection('content')
