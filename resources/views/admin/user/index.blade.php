@extends('layouts/layout')

@section('title', 'UNF Inventory Admin')

@section('content')

  <div class="container">
    <div class="content">
      @section('banner-title')
        Manage Users
      @endsection

      @include('snippets/banner')
      <div class="box">
        <div class="users box">
          <div class="legend">
            <label class="label"><div class='role-icon admin'></div>Admin</label>
            <label class="label"><div class='role-icon normal'></div>Normal User</label>
            <label class="label"><div class='role-icon'></div>Guest</label>
          </div>
          @foreach ($users as $user)
            <a class='user' href='/users/{{ $user->id }}'>
              <div class="role-icon {{ $user->role->name }}"></div>
              {{ $user->name }}
            </a>
            <hr class='hr'>
          @endforeach
        </div>
        <div class="buttons">
          <a class='button is-link' href="/admin">Back</a>
          <a class='button is-success' href="/users/create">Create New User</a>
        </div>
      </div>
    </div>

  </div>

@endsection('content')
