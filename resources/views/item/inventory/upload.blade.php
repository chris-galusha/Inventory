@extends('layouts/layout')

@section('title', 'UNF Inventory')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        Inventory from file
      @endsection

      @include('snippets/banner')

      <div class="box">

      <form class="form" action="/inventory/upload/update" enctype="multipart/form-data" method="post">
        @csrf

        <h4>Upload a csv with inventory numbers <br> of items to be inventoried</h4>

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
        <div class='control'>
          <a href="/items" class='button is-link'>Back</a>
          <button class='button is-primary' type="submit">Upload</button>
        </div>
      </form>
    </div>
    </div>
  </div>



@endsection('content')
