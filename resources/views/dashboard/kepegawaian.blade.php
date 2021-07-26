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
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th scope="col">{{ __('Jenis Cuti') }}</th>
            <th scope="col">{{ __('Sisa Cuti') }}</th>
          </tr>
        </thead>

        {{-- TODO : generate stuffs --}}
        <tbody class="list">
          <tr>
            <th scope="col">Tahunan</th>
            <th scope="col">99999</th>
          </tr>
        </tbody>

      </table>
    </div>
  </div>
</div>

  </div>
</div>
  @include('layouts.footers.nav')
</div>


@endsection
