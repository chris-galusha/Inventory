@extends('layouts/layout')

@section('title', 'UNF Confirm Delete')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to delete {{ $total }} Items</h1>
      </div>
      <form class="form" action="/items/mass/delete" method="post">
        @csrf
        @method('delete')
        <div class='control'>
          <a href="/items" class='button is-link'>Back</a>
          <button class='button is-danger' type="submit">Delete</button>
        </div>
      </form>
    </div>
  </div>

  

@endsection('content')
