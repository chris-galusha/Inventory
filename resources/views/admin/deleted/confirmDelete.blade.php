@extends('layouts/layout')

@section('title', 'UNF Confirm Delete')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to <span class='underlined'>PERMANENTLY DELETE</span> {{ $total }} Items</h1>
      </div>
      <form class="form" action="/admin/deleted/delete" method="get">
        @csrf
        <div class='control'>
          <a href="/admin/deleted/restore" class='button is-link'>Back</a>
          <button class='button is-danger' type="submit">Delete</button>
        </div>
      </form>
    </div>
  </div>

  

@endsection('content')
