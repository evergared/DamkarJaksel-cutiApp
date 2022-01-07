@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')


<div class="container-fluid mt--7">

        <div id="app">
            <admin-calendar eventSources="{{route('calendar.json.all')}}"></admin-calendar>
        </div>

  @include('layouts.footers.nav')


</div>

@endsection

@push('js')
    <script src="{{ asset('js/fullCalendar.js') }}"></script>
@endpush