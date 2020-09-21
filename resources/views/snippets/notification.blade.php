@if (session()->has('message') && $errors->any())
  <div class="notification is-danger is-bottom-right">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
      <li>
        {!! str_replace("\n", "<br>", session()->get('message')) !!}
      </li>
    </ul>
  </div>
@else
  @if (session()->has('message'))
    <div class="notification is-info is-bottom-right">
      <p>
        {!! str_replace("\n", "<br>", session()->get('message')) !!}
      </p>
    </div>
  @endif
  @if ($errors->any())
    <div class="is-danger notification errors is-bottom-right">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ preg_replace("/(columns.)([a-z])/", '${2}', $error) }}</li>
        @endforeach
      </ul>
    </div>
  @endif
@endif
