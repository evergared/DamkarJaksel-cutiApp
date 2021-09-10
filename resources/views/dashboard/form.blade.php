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

            @if($errors->has('form_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-inner--text">{{ $errors->first('form_error') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            @endif

            @if(session('form_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-inner--text"><strong>{{ session('form_success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            @endif

            <form role="form" method="post" action="{{ route('submit-cuti') }}">
            @csrf
              <div class="form-group">

                {{-- Bagian NRK --}}
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-address-card"></i></span>
                  </div>
                  <input class="form-control" id="nrk" name="nrk" placeholder="{{ __('NRK') }}" type="text" @if(auth()->user()->nip !== null) value = "{{ 'auth()->user()->nip' }}" @endif disabled>
                </div>

                {{-- Bagian datepicker --}}
                <div class="row justify-content-center align-items-center input-daterange datepicker mb-3 ">
                  <div class="input-group col-lg-5">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input autocomplete="off" class="form-control" id="tMulai" name="tMulai" placeholder="{{ __('Tanggal Mulai') }}" type="text" data-provide="datepicker">
                  </div>
                  <span class="my-2 mb-2"><small>{{ __('Sampai Dengan') }}</small></span>
                  <div class="input-group col-lg-5">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input autocomplete="off" class="form-control" id="tSelesai" name="tSelesai" placeholder="{{ __('Tanggal Selesai') }}" type="text">
                  </div>
                </div>

                {{-- Bagian Dropdown Jenis Cuti --}}

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-clipboard"></i></span>
                  </div>
                  <select autocomplete="off" class="form-control" id="jCuti" name="jCuti" placeholder="{{ __('Jenis Cuti') }}" type="text">
                      @if($dd_jcuti !== null)
                      @foreach( $dd_jcuti as $cuti)
                        <option value="{{ $cuti }}">{{ $cuti }}</option >
                      @endforeach
                      @endif
                  </select>
                </div>

                {{-- Bagian Alasan Cuti --}}
                <div class="input-group">
                  {{-- utk attrib textarea Class="form-control" biasa, menyebabkan bug saat di resize --}}
                  <textarea class="form-control" id="aCuti" name="aCuti" style="resize:none;" rows="5" placeholder="{{ __('Alasan Cuti') }}"></textarea>
                </div>

                {{-- Bagian tombol submit --}}
                <div class="text-center">
                  <a class="btn btn-primary my-4 text-white" data-toggle="modal" data-target="#notif" onclick="updateModal()">{{ __('Submit') }}</a>
                </div>

                {{-- Bagian modal konfirmasi --}}
                <x-modal id="notif" title="Cek Kembali Data Anda">
                <x-slot name="message">
                  <div class="align-items-left" id="modal-message">
                    <label><strong>NRK : </strong></label><span id="modal-nrk"></span><br>
                    <label><strong>Waktu : </strong></label><span id="modal-mCuti"></span>
                    <span> s.d. </span><span id="modal-sCuti"></span><br>
                    <label><strong>Jenis Cuti : </strong></label><span id="modal-jCuti"></span><br>
                    <label><strong>Alasan : </strong></label><span id="modal-aCuti"></span><br>
                    <p class="mt-2"><strong>Submit permintaan cuti dengan data diatas?</strong>
                  </div>
                </x-slot>
                <x-slot name="footer">
                  <a class="btn btn-secondary my-2" data-toggle="modal" data-target="#notif">{{ __('Batal') }}</a>
                  <button class="btn btn-primary my-2 text-white" type='submit'>{{ __('OK') }}</button>
                </x-slot>
                </x-modal>

                {{-- Script untuk fungsi modal dan form submit --}}
                <script>
                  function updateModal()
                  {
                    var $nrk = " " + document.getElementById('nrk').value;
                    var $tanggalMulai = " " + document.getElementById('tMulai').value;
                    var $tanggalSelesai = " " + document.getElementById('tSelesai').value;
                    var $jenisCuti = " " + document.getElementById('jCuti').value;
                    var $alasanCuti = " " + document.getElementById('aCuti').value;

                    document.getElementById('modal-nrk').innerHTML = $nrk;
                    document.getElementById('modal-mCuti').innerHTML = $tanggalMulai;
                    document.getElementById('modal-sCuti').innerHTML = $tanggalSelesai;
                    document.getElementById('modal-jCuti').innerHTML = $jenisCuti;
                    document.getElementById('modal-aCuti').innerHTML = $alasanCuti;
                  }

                  
                </script>

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

@push('js')
{{-- Datepicker --}}
<script src="{{ asset('assets') }}/vendor/js-cookie/js.cookie.js"></script>
<script src="{{ asset('assets') }}/vendor/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.js"></script>
<script src="{{ asset('assets') }}/vendor/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.id.min.js"></script>
<script src="{{ asset('js') }}/calendar-tools.js"></script>
@endpush
