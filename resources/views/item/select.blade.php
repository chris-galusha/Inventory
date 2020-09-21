@extends('layouts/layout')

@section('title', 'NUF Inventory')

@section('content')

  <div class="container">
    <div class="content">
      @section('banner-title')
        Select Items by CSV
      @endsection

      @include('snippets/banner')

      <div class="box">
        <form class="form" action="/items/select" enctype="multipart/form-data" method="post">
          @csrf

          <div class="file has-name is-boxed is-info">
            <label class="file-label">
              <input class="file-input" type="file" name="file">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Upload CSV
                </span>
              </span>
              <span class="file-name">
                File Name...
              </span>
            </label>
          </div>

          <div class="sql-options">
            <div class="field">
              <label>
                Delimiter:
                <div class="control">
                  <input type="text" name="options[delimiter]" value=",">
                </div>
              </label>
            </div>
            <div class="field">
              <label class='checkbox'>
                <div class="control">
                  <input type="checkbox" name='options[include-decommissioned]' value='1'/>
                </div>
                Include Decommissioned
              </label>
            </div>
          </div>

          <div class='buttons'>
            <a href="/items" class='button is-link'>Back</a>
            <button class='button is-primary' type="submit">Select</button>
          </div>
        </form>
      </div>

    @endsection
