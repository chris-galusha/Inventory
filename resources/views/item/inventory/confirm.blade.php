@extends('layouts/layout')

@section('title', 'UNF Confirm Inventory')

@section('content')
  <div class="container">
    <div class="content">
      @section('banner-title')
        You are about to inventory {{ $total }} Items
      @endsection

      @include('snippets/banner')

      <div class="box">
      <form class="form" action="/inventory" method="get">
        @csrf
        <div class='control'>
          <a href="/items" class='button is-link'>Back</a>
          <a href="/inventory/upload" class='button is-info'>Inventory from CSV</a>
          <button class='button is-primary' type="submit">Inventory</button>
        </div>
      </form>
    </div>
  </div>
  </div>



@endsection('content')
