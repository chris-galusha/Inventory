@extends('layouts/layout')

@section('title', 'UNF Confirm Delete')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to <span class='underlined'>PERMANENTLY DELETE</span> Column: {{ $column->display_name }}</h1>
      </div>
      <form class="form" action="/columns/{{ $column->id }}" method="post">
        @csrf
        @method('DELETE')
        <a class='button is-link' href="/columns/{{ $column->id }}/edit">Back</a>
        <button type="submit" class='button is-danger'>Delete Column</button>
      </form>
    </div>
  </div>

  

@endsection('content')
