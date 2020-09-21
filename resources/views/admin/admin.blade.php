@extends('layouts/layout')

@section('title', 'UNF Inventory Admin')

@section('content')

  <div class="container">
    <div class="content">
      @section('banner-title')
        Administrator Panel
      @endsection

      @include('snippets/banner')

      <div class="box">
        <div class="admin-buttons">
          <a class='button is-primary' href="/columns">Database Columns</a>
          <a class='button is-warning' href="/admin/deleted/restore">Manage Deleted Items</a>
          <a class="button is-purple" href="/sql/backup">Backup Database</a>
          <a class='button is-success' href="/sql/recover/select">Recover Database</a>
          <a class='button is-primary' href="/users">Manage Users</a>
        </div>
        <div class="buttons">
          <a class='button is-link' href="/items">Back</a>
        </div>
      </div>
    </div>

  </div>



@endsection('content')
