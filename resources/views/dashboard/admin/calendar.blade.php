@extends('layouts.app')
@section('content')

<div id="app">
    <admin-calendar></admin-calendar>
</div>

@endsection

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush