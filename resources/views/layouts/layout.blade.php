<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'NUF Inventory')</title>
  <script src="https://kit.fontawesome.com/a00fcd0e30.js"></script>
  <script src="/js/jquery.js" type="text/javascript"></script>
  <script src="/js/app.js" type="text/javascript"></script>
  <link rel="stylesheet" href="/css/app.css">
</head>
<body>

  @yield('content')

  @include('snippets/notification')

</body>
</html>
