@extends('layouts/layout')

@section('title', 'UNF Login')

@section('content')
  <div class="container">
    <div class="content box">
      <div class="hero">
        <h1>{{ __('Login') }}</h1>
      </div>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
          <label class="label">{{ __('E-Mail Address') }}</label>

          <div class="control">
            <input id="email" type="email" class="input @error('email') is-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>
            <div class="is-flex">
              @error('email')
                <span class="is-danger notification" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label">{{ __('Password') }}</label>

            <div class="control">
              <input id="password" type="password" class="input @error('password') is-danger @enderror" name="password" required autocomplete="current-password">
              </div>
              <div class="is-flex">
                @error('password')
                  <span class="is-danger notification" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="field">
              <div class="control">
                <label class="label">
                  <input class="" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  {{ __('Remember Me') }}
                </label>
              </div>
            </div>

            <div class="field">
              <div class="buttons">
                <a href="/" class='button is-link'>Back</a>
                <button type="submit" class="button is-primary">
                  {{ __('Login') }}
                </button>

                <a href="{{ route('register') }}" class='button is-success'>Register Instead</a>

                @if (Route::has('password.request'))
                  <a class="button is-info" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                  </a>
                @endif
              </div>
            </div>
          </form>
        </div>
      </div>

      

    @endsection
