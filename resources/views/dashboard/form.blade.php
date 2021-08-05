@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')

<div class="container-fluid mt--7 ">
  <div class="row justify-content-center">

    {{-- Bagian form pengajuan cuti --}}
    <div class="col-lg-8 col-md-7">
      <div class="card bg-secondary shadow border-0 xl-12">
        <div class="card-body">
          <div class="text-center">
            <h1>Form Pengajuan Cuti</h1>
          </div>
          <div class="col">
            <form role="form" method="post" action="{{ route('submit_cuti') }}">
            @csrf
              <div class="form-group">

                {{-- Bagian NRK --}}
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-address-card"></i></span>
                  </div>
                  <input class="form-control" name="nrk" placeholder="{{ __('NRK') }}" type="text" disabled>
                </div>

                {{-- Bagian datepicker --}}
                <div class="row justify-content-center align-items-center input-daterange datepicker mb-3 ">
                  <div class="input-group col-lg-5">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input class="form-control" name="tMulai" placeholder="{{ __('Tanggal Mulai') }}" type="text">
                  </div>
                  <span class="my-2 mb-2"><small>{{ __('Sampai Dengan') }}</small></span>
                  <div class="input-group col-lg-5">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input class="form-control" name="tSelesai" placeholder="{{ __('Tanggal Selesai') }}" type="text">
                  </div>
                </div>

                {{-- Bagian Dropdown Jenis Cuti --}}
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-clipboard"></i></span>
                  </div>
                  <input class="form-control" name="jCuti" placeholder="{{ __('Jenis Cuti') }}" type="text">
                </div>

                {{-- Bagian Alasan Cuti --}}
                <div class="input-group">
                  {{-- utk attrib textarea Class="form-control" biasa, menyebabkan bug saat di resize --}}
                  <textarea class="form-control" name="aCuti" style="resize:none;" rows="5" placeholder="{{ __('Alasan Cuti') }}"></textarea>
                </div>

                {{-- Bagian tombol submit --}}
                <div class="text-center">
                  <button class="btn btn-primary my-4 text-white" data-toggle="modal" data-target="#notif">{{ __('Submit') }}</button>
                </div>

                <x-modal id="notif">


                </x-modal>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>



    {{-- Bagian tampilan sisa cuti --}}
    <div class="col-lg-4 md-7">
      <div class="card bg-secondary shadow border-0 xl-4">
        <div class="card-body">
          <div class="text-center mb-4">
              <strong>Sisa Cuti</strong>
          </div>
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
