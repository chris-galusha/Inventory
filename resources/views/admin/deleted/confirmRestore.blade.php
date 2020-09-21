@extends('layouts/layout')

@section('title', 'UNF Confirm Restore')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to restore {{ $total }} Items</h1>
      </div>
      <form class="form" action="/admin/deleted/restore/update" method="get">
        @csrf
        <div class='control'>
          <a href="/admin/deleted/restore" class='button is-link'>Back</a>
          <button class='button is-primary' type="submit">Restore</button>
        </div>
      </form>
    </div>
  </div>

  

@endsection('content')
