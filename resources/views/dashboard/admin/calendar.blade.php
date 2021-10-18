@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row">

        <div id="app">
            <admin-calendar></admin-calendar>
        </div>

  </div>
</div>

@endsection

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush