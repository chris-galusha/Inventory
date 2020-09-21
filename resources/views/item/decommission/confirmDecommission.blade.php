@extends('layouts/layout')

@section('title', 'UNF Inventory Decommission')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>You are about to decommission {{ $total }} Items</h1>
      </div>
      <form class="form" action="/decommission" method="post">
        @csrf

        <div class="field">
          <div class="control">
            <label>Reason for decommissioning:</label>
            <input type="text" class='input' name="reason" value="old, lifecycle" placeholder="Reason for decommissioning">
          </div>
        </div>

        <div class='control'>
          <a href="/items" class='button is-link'>Back</a>
          <a href="/decommission/upload" class='button is-info'>Decommission from CSV</a>
          <button class='button is-warning' type="submit">Decommission</button>
        </div>
      </form>
    </div>
  </div>

  

@endsection('content')
