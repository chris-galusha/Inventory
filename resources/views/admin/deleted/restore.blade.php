@extends('layouts/layout')

@section('title', 'Restore Deleted')

@section('content')
  <div class="container restore">
    <div class="content">

      @section('banner-title')
        Restore Deleted Items
      @endsection

      @include('snippets/banner')

      <div class="box">
        <form class="form restore" action="/admin/deleted/restore/confirm" method="post">
          @csrf
          <div class="field">
            <div class="control">
              <button type="button" class='button is-info is-outlined' name="select-all">Select All</button>
              <button type="button" class="button is-info is-outlined" name="clear-all">Clear Selection</button>
            </div>
          </div>
          @if (!$deleted_items->count())
            <p>No items to be restored.</p>

          @else
            <div class="box scrolling-table-60">

              @foreach ($deleted_items as $item)
                <div class="field">
                  <label class='label'>
                    <input type="checkbox" class='select-item' name="item-ids[]" value="{{ $item->id }}">
                    {{ $item->description }}
                    {{ $item->inventory_number ? '('.$item->inventory_number.')' : '' }}
                  </label>
                  <hr>
                </div>

              @endforeach
            </div>


            {{ $deleted_items->links() }}

            <div class="field">
              <div class="">
                <button type="submit" name='action' value="restore" class='button is-primary'>Restore Selected</button>
                <button type="submit" name='action' value='delete' class='button is-danger'>Permanently Delete Selected</button>
              </div>
              <div class="buttons">
                <button type="submit" name='action' value='restore-all' class='button is-warning'>Restore All</button>
                <button type="submit" name='action' value='delete-all' class='button is-danger'>Permanently Delete All</button>
              </div>
            </div>

          @endif

        </form>

        <div class="buttons">
          <a href="/admin" class='button is-link'>Back</a>
        </div>
      </div>

    </div>
  </div>



@endsection('content')
