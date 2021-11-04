@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')

<div class="container-fluid mt--7 ">
  <div class="row justify-content ">

  <div class="col-md-7 col-sm" id="app">
    <form-cuti v-bind:nip = '{{ auth()->user()->nip }}'></form-cuti>
  </div>


    {{-- Bagian tampilan sisa cuti --}}
    <div class="col-md-5 col-sm">
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

              <tbody class="list">
          
              @if(auth()->user()->is_pjlp)
              @inject('cuti','App\Services\Stat\SisaCuti')

                <tr>
                  <th scope="col">Tahunan</th>
                  <th scope="col">{{ $cuti::$sisaTahunan }}</th>
                </tr>

              @elseif(auth()->user()->is_asn)
              @inject('cuti','App\Services\Stat\SisaCuti')

                <tr>
                  <th scope="col">Tahunan</th>
                  <th scope="col">{{ $cuti::$sisaTahunan }}</th>
                </tr>
                <tr>
                  <th scope="col">N1</th>
                  <th scope="col">{{ $cuti::$n1 }}</th>
                </tr>
                <tr>
                  <th scope="col">N2</th>
                  <th scope="col">{{ $cuti::$n2 }}</th>
                </tr>
              @endif

                
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

<script src="{{ asset('js') }}/calendar-tools.js">
</script>
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
