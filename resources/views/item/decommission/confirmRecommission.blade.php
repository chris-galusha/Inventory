@extends('layouts/layout')

@section('title', 'UNF Inventory Recommission')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to recommission {{ $total }} Items</h1>
      </div>
      <form class="form" action="/decommission/recommission" method="get">
        @csrf

        <div class='control'>
          <a href="/items" class='button is-link'>Back</a>
          <button class='button is-success' type="submit">Recommisison</button>
        </div>
      </form>
    </div>
  </div>

  

@endsection('content')
