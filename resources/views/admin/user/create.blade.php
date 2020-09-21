@extends('layouts/layout')

@section('title', 'UNF Inventory Admin')

@section('content')

  <div class="container">
    <div class="content">
      @section('banner-title')
        New User
      @endsection

      @include('snippets/banner')

      <div class="box">
        <form class="form" action="/users" method="post">
          @csrf

          <div class="field">
            <label class='label'>
              Name:
              <div class="control">
                <input type="text" class='input' name="name" value="{{ old('name') ?? '' }}" placeholder="Name..." required>
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Email:
              <div class="control">
                <input type="text" class='input' name="email" value="{{ old('email') ?? '' }}" placeholder="Email..." required>
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Password:
              <div class="control">
                <input type="password" class='input' name="password" placeholder="Password..." required>
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Confirm Password:
              <div class="control">
                <input type="password" class='input' name="password-confirm" placeholder="Password Confirm..." required>
              </div>
            </label>
          </div>

          <div class="field">
            <label class='label'>
              Role:
              <br>
              <div class="select">
                <select class="" name="role" required>
                  <option value="guest">Guest</option>
                  <option value="normal">Normal User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </label>
          </div>

          <div class="buttons">
            <a class='button is-link' href="/users">Back</a>
            <button type="submit" class='button is-success'>Create</button>
          </div>
        </form>
      </div>
    </div>

  </div>



@endsection('content')
