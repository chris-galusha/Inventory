@extends('layouts/layout')

@section('title', 'UNF Recover')

@section('content')
  <div class="container">
    <div class="content">
      @section('banner-title')
        Recover Database
      @endsection

      @include('snippets/banner')

        <div class="box">
          <h3>Select a SQL file or upload one</h3>
          <form class="form sql-select box" action="/sql/recover" method="post">
            @csrf

            @foreach ($sql_files as $sql_file)
              <div class="field sql-file">
                <button class='button is-purple' type="submit" name="backup-name" value="{{ $sql_file }}">{{ $sql_file }}</button>
                <hr class="hr">
              </div>

            @endforeach

          </form>
          <div class='buttons'>
            <a href="/admin" class='button is-link'>Back</a>
            <a href="/sql/recover/upload" class='button is-info'>Upload SQL Instead</a>
          </div>
        </div>
    </div>
  </div>

@endsection('content')
