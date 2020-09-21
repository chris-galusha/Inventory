@extends('layouts/layout')

@section('title', 'UNF Confirm Delete')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to <span class='underlined'>PERMANENTLY DELETE</span> user: {{ $user->name }}</h1>
      </div>
      <form class="form" action="/users/{{ $user->id }}" method="post">
        @csrf
        @method('DELETE')
        <div class='control'>
          <a href="/users/{{ $user->id }}/edit" class='button is-link'>Back</a>
          <button class='button is-danger' type="submit">Delete</button>
        </div>
      </form>
    </div>
  </div>



@endsection('content')
