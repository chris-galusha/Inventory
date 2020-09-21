@extends('layouts/layout')

@section('title', 'UNF Inventory Admin')

@section('content')

  <div class="container">
    <div class="content">

      @section('banner-title')
        Edit User: {{ $user->name }}
      @endsection

      @include('snippets/banner')

      <div class='box'>
        <form class="form box" action="/users/{{ $user->id }}" method="post">
          @csrf
          @method('PATCH')

          <div class="field">
            <label class='label'>
              Name:
              <div class="control">
                <input type="text" class='input' name="name" value="{{ old('name') ?? $user->name }}" placeholder="Name...">
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Email:
              <div class="control">
                <input type="text" class='input' name="email" value="{{ old('email') ?? $user->email }}" placeholder="Email...">
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Password:
              <div class="control">
                <input type="password" class='input' name="password" placeholder="Password...">
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Confirm Password:
              <div class="control">
                <input type="password" class='input' name="password-confirm" placeholder="Password Confirm...">
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Role:
              <br>
              <div class="select">
                <select class="" name="role">
                  <option {{ $user->role->name == 'guest' ? 'selected' : '' }} value="guest">Guest</option>
                  <option {{ $user->role->name == 'normal' ? 'selected' : '' }} value="normal">Normal User</option>
                  <option {{ $user->role->name == 'admin' ? 'selected' : '' }} value="admin">Admin</option>
                </select>
              </div>
            </label>
          </div>

          <div class="buttons">
            <a class='button is-link' href="/users/{{ $user->id }}">Back</a>
            <button type="submit" class='button is-purple'>Update</button>
          </div>
        </form>

        <div class="box">
          <form class="form" action="/users/confirmDelete/{{ $user->id }}" method="post">
            @csrf
            <button type="submit" class="button is-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>

  </div>



@endsection('content')
