@extends('layouts/layout')

@section('title', 'UNF Columns')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        Column: {{ $column->display_name }}
      @endsection

      @include('snippets/banner')

      <div class="center-box">
        <div class="box">
          <div class="box">
            <ul>
              <li>Type: {{ $column->type->display_name }}</li>
              <li>Displayed: {{ $column->display ? 'Yes' : ' No' }}</li>
              <li>Protected: {{ $column->protected ? 'Yes' : ' No' }}</li>
              <li>Required: {{ $column->required ? 'Yes' : ' No' }}</li>
            </ul>
          </div>
          <div class="buttons">
            <a class='button is-link' href="/columns">Back</a>
            <a class='button is-purple' href="/columns/{{ $column->id }}/edit">Edit</a>
          </div>
        </div>
      </div>
    </div>

  </div>



@endsection('content')
