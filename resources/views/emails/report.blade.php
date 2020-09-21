<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>

  <font face=""verdana"">
    <h1>Report "{{ $report->name }}" Ran on {{ nowDateTime() }}</h1>
    @if ($items->count() == 0)
      <h2>{{ $report->description }} <br> No report was compiled, as no items met the criteria to be included in the report.</h2>
    @else
      <h2>{{ $report->description }} <br> {{ $statistics['query-count'] }} items were included in the report.</h2>
    @endif
  </font>
</body>
</html>
