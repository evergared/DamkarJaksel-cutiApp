@extends('layouts.app')
@section('content')

<div class="container-fluid mt--7 ">
  <div class="row justify-content-center">

        <div id="app">
            <admin-calendar></admin-calendar>
        </div>

  </div>
</div>

@endsection

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush