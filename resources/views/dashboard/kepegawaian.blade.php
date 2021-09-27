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
          <h2>{{ __('List Akun Pegawai') }}</h2>
        </div>
        <div class="nav-wrapper text-right">
          {{-- TODO : Kondisi if else untuk hide/show tombol berdasarkan siapa yang masuk --}}
            <ul class="nav nav-pills flex-column flex-md-row" id="tabs-pegawai" role="tablist">
              <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-pegawai-asn" data-toggle="tab" href="#tab-asn" role="tab" aria-controls="tab-asn" aria-selected="true">ASN</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-pegawai-pjlp" data-toggle="tab" href="#tab-pjlp" role="tab" aria-controls="tab-pjlp" aria-selected="false">PJLP</a>
              </li>
            </ul>
        </div>
      </div>
    </div>

    {{-- body tabel --}}
    <div class="card-body">
      <div class="col">
        <div class="tab-content" id="tab-content-pegawai">

          <div class="tab-pane fade show active" id="tab-asn" role="tabpanel" aria-labelledby="tab-asn-tab">
            <h3>Tabel List ASN</h3>
            @include('datatable.kepegawaian.pegawai-asn')
          </div>
        
          <div class="tab-pane fade" id="tab-pjlp" role="tabpanel" aria-labelledby="tab-pjlp-tab">
            <h3>Tabel List PJLP</h3>
            @include('datatable.kepegawaian.pegawai-pjlp')
          </div>
        

        </div>
      </div>
    </div>


  </div>
</div>
  @include('layouts.footers.nav')
</div>







@endsection
