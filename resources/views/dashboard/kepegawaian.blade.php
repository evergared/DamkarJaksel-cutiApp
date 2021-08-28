@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards')

<div class="container-fluid mt--7">

<div class="col-lg-12 md-7">
  <div class="card bg-secondary shadow border-0 xl-4">

  {{ $tabAction }}

    {{-- header tabel --}}
<div class="card-header">
  <div class="row align-items-center">
    <div class="col text-left">
      <h3>{{ __('List Akun Pegawai') }}</h3>
    </div>
    <div class="col text-right">
      {{-- TODO : Kondisi if else untuk hide/show tombol berdasarkan siapa yang masuk --}}
      <span>
        <a class="btn btn-sm btn-primary text-white" action="{{ $tabAction == 'asn' }}">ASN</a>
      </span>
      <span>
        <a class="btn btn-sm btn-primary text-white" action="{{ $tabAction == 'pjlp' }}">PJLP</a>
      </span>
    </div>
  </div>
</div>

{{-- body tabel --}}
<div class="card-body">
  <div class="col">
    <div class="row align-items-center">

    @if($tabAction === 'asn')
    <h2>Tabel List ASN</h2>
    <div class="table-responsive">
      <table class="table asn">
          <thead>
              <tr>
                  <th>NO</th>
                  <th>NIP</th>
                  <th>NRK</th>
                  <th>Nama</th>
                  <th>Golongan</th>
                  <th>Jabatan</th>
                  <th>NIP Atasan</th>
                  <th>Pendidikan</th>
              </tr>
          </thead>
          <tbody></tbody>
      </table>
    </div>

    @elseif($tabAction === 'pjlp')
    <h2>Tabel List PJLP</h2>
    <div class="table-responsive">
      <table class="table pjlp">
          <thead>
              <tr>
                  <th>NO</th>
                  <th>NIP</th>
                  <th>No PJLP</th>
                  <th>Nama</th>
                  <th>Jenis Kontrak</th>
                  <th>NIP Atasan</th>
                  <th>Pendidikan</th>
              </tr>
          </thead>
          <tbody></tbody>
      </table>
    </div>
    @endif

    </div>
  </div>
</div>


  </div>
</div>
  @include('layouts.footers.nav')
</div>

<script type="text/javascript">
    $(function (){
        var table = $('.asn').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('list.asn') }}",
            columns:[
                {data: 'DT_RowIndex', name:'DT_RowIndex'},
                {data: 'nip', name:'nip'},
                {data: 'nrk', name:'nrk'},
                {data: 'nama', name: 'nama'},
                {data: 'golongan', name:'golongan'},
                {data: 'jabatan', name:'jabatan'},
                {data: 'nip_atasan',name:'nip_atasan'},
                {data: 'pendidikan',name: 'pendidikan'}
            ]

        });
    });
</script>

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush

@endsection
