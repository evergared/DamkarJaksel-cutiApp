@extends('layouts.app')

@section('content')

@auth
    @if(in_array('ASN',Auth::user()->roles))
        User adalah ASN tulen
    @endif
@endauth

    Testis


@endsection

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>


@endpush