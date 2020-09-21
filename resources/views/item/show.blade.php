@extends('layouts/layout')

@section('title', 'Item Description')

@section('content')
  <div class="container">
    <div class="content">

      @section('banner-title')
        {{ $item->description }}
      @endsection

      @include('snippets/banner')

      <div class="box">

      <div class="item-show box">
        @foreach ($columns as $column)
          @php
          $name = $column->name;
          @endphp
          <div class="{{$column->type->name == 'textarea' ? 'flex-column' : '' }}">
            <label class="label">{{ $column->display_name }}:</label>
            <span class='{{ $column->type->name == 'textarea' ? 'box' : '' }}'>{{ $item->$name ? $item->$name : "N/A"}}</span>
          </div>
          <hr>
        @endforeach

      </div>
      <div class='buttons'>
        <a class="button is-link" href="/items">Back</a>
        <a class="button is-primary" href="/items/{{ $item->id }}/edit">Edit</a>
      </div>
    </div>
    </div>
  </div>

@endsection('content')
