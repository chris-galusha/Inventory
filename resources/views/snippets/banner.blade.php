<div class="header box">
  <h1 class="">@yield('banner-title', 'NUF Inventory')</h1>
  <div class="login-buttons">
    <a href="/items" class="button is-info">Home</a>
    <a href="{{ checkAuth() ? route('logout') : route('login') }}" class='button is-purple'>{{ checkAuth() ? 'Logout' : 'Login'}}</a>
    <a href="{{ route('register') }}" class='button is-success'>Create Account</a>
    @if (userIsAdmin())
      <a href="/admin" class='button is-info'>Administrator Panel</a>
    @endif
  </div>
</div>
