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
              <h3>{{ __('Report Data Cuti') }}</h3>
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

        <div class="card-body">

        </div>

    </div>
  </div>

@include('layouts.footers.nav')

</div>

@endsection
