@extends('layouts.app')

@section('content')

@auth
    @if(in_array('ASN',Auth::user()->roles))
        User adalah ASN tulen
    @endif
@endauth
<div id="app">
    <button class="btn" data-toggle="modal" data-target="#modal">test</button>
    
<div class="modal fade" tabindex="-1" id="modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-body">
            
        <example-component></example-component>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
            
        </div>
    </div>
</div>

<form-cuti v-bind:nip = "123456"></form-cuti>
            </div>

@endsection

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>


@endpush

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush