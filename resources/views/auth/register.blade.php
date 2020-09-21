@extends('layouts/layout')

@section('title', 'UNF Register')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>{{ __('Register') }}</h1>
      </div>
      <form method="POST" class='form' action="{{ route('register') }}">
        @csrf

        <div class="field">
          <label class="label">{{ __('Name') }}</label>

          <div class="control">
            <input id="name" type="text" class="input @error('name') is-danger @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            </div>
            <div class="is-flex">
              @error('name')
                <span class="notification is-danger" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label">{{ __('E-Mail Address') }}</label>

            <div class="control">
              <input id="email" type="email" class="input @error('email') is-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
              </div>
              <div class="is-flex">
                @error('email')
                  <span class="notification is-danger" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

            </div>

            <div class="field">
              <label class="label">{{ __('Password') }}</label>

              <div class="control">
                <input id="password" type="password" class="input @error('password') is-danger @enderror" name="password" required autocomplete="new-password">
                </div>
                <div class="is-flex">
                  @error('password')
                    <span class="notification is-danger" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

              </div>

              <div class="field">
                <label class="label">{{ __('Confirm Password') }}</label>

                <div class="field">
                  <input id="password-confirm" type="password" class="input" name="password_confirmation" required autocomplete="new-password">
                </div>
              </div>

              <div class="field">
                <div class="buttons">
                  <a href="/" class='button is-link'>Back</a>
                  <button type="submit" class="button is-primary">{{ __('Register') }}</button>
                  <a href="{{ route('login') }}" class='button is-success'>Login Instead</a>
                </div>
              </div>
            </form>
          </div>
        </div>

        

      @endsection
