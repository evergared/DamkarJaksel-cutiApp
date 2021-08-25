@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards')

<div class="container-fluid mt--7">

<div class="col-lg-12 md-7">
  <div class="card bg-secondary shadow border-0 xl-4">

    {{-- header tabel --}}
<div class="card-header">
  <div class="row align-items-center">
    <div class="col text-left">
      <h3>{{ __('List Akun Pegawai') }}</h3>
    </div>
    <div class="col text-right">
      <span>
        <a class="btn btn-sm btn-primary" href="#">ASN</a>
      </span>
      <span>
        <a class="btn btn-sm btn-primary" href="#">PJLP</a>
      </span>
    </div>
  </div>
</div>

{{-- body tabel --}}
<div class="card-body">
  <div class="col">
    <div class="row align-items-center">
      {{-- Tampilkan berapa banyak data per laman --}}
      <div class="row justify-content-start">
        <small>{{ __('Menampilkan')}} </small>
        <input class="form-control-sm form-control-alternative col-lg-2 text-xs" type="text" name="">
        <small> {{ __(' Entri')}}</small>
      </div>
      <div class="col-lg-6">

      </div>
      {{-- Cari data --}}
      <div class="row ">
  <form class="" action="#" method="post">
    <div class="row form-group justify-content-end">
    <div class="input-group input-group-sm col-lg-6">
      <div class="input-group-prepend input-group-alternative">
      <span class="input-group-text"><i class="ni ni-zoom-split-in"></i></span>
      </div>
      <input class="form-control form-control-alternative" type="text" name="" placeholder="{{ __('Cari') }}">
    </div>
    <a class="btn btn-sm btn-primary md-2"><small class="text-white">{{ __('Cari') }}</small></a>
    </div>
  </form>
  </div>
    </div>
    {{-- Tabel --}}
    <div class="table-responsive">
      {{!! $dataTable->table() !!}}
    </div>
  </div>
</div>

{{!! $dataTable->scripts() !!}}

  </div>
</div>
  @include('layouts.footers.nav')
</div>



@push('datatbles')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush

@endsection
