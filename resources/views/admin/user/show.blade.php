@extends('layouts/layout')

@section('title', 'UNF Inventory Admin')

@section('content')

  <div class="container">
    <div class="content">

      @section('banner-title')
        {{ $user->name }}
      @endsection

      @include('snippets/banner')

      <div class="box">
      <div class="box">
        <ul>
          <li>Email: {{ $user->email }}</li>
          <li>Role: {{ $user->role->name ? ucfirst($user->role->name) : 'No Role' }}</li>
        </ul>
      </div>
      <div class="buttons">
        <a class='button is-link' href="/users">Back</a>
        <a class='button is-purple' href="/users/{{ $user->id }}/edit">Edit</a>
      </div>
    </div>
  </div>
  </div>



@endsection('content')
