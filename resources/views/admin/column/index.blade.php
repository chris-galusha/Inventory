@extends('layouts/layout')

@section('title', 'UNF Columns')

@section('content')
  <div class="container">
    <div class="content">
      @section('banner-title')
        Database Columns
      @endsection

      @include('snippets/banner')
      <div class="box">
        <div class="column-links scrolling-table-75 box">
          @foreach ($columns as $column)
            <a href="/columns/{{ $column->id }}">
              {{ $column->display_name.' ('.$column->name.')' }}
            </a>
            <hr>
          @endforeach
        </div>
        <div class="buttons">
          <a class='button is-link' href="/admin">Back</a>
          <a class='button is-success' href="/columns/create">New Column</a>
        </div>
      </div>
    </div>

  </div>



@endsection('content')
