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
          </div>
        
          <div class="tab-pane fade" id="tab-pjlp" role="tabpanel" aria-labelledby="tab-pjlp-tab">
            <h3>Tabel List PJLP</h3>
            <div class="table-responsive">
              <table class="table pjlp">
                  <thead>
                      <tr>
                          <th>NO</th>
                          <th>NIP</th>
                          <th>No PJLP</th>
                          <th>Nama</th>
                          <th>Golongan</th>
                          <th>Jenis Kontrak</th>
                          <th>NIP Atasan</th>
                          <th>Pendidikan</th>
                      </tr>
                  </thead>
                  <tbody></tbody>
              </table>
            </div>
          </div>
        

        </div>
      </div>
    </div>


  </div>
</div>
  @include('layouts.footers.nav')
</div>

{{-- TODO : if else condition agar script hanya muncul saat diperlukan --}}
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

<script type="text/javascript">
    $(function (){
        var table = $('.pjlp').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('list.pjlp') }}",
            columns:[
                {data: 'DT_RowIndex', name:'DT_RowIndex'},
                {data: 'nip', name:'nip'},
                {data: 'no_pjlp', name:'no_pjlp'},
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
{{-- TODO : Buat css baru lagi untuk membenahi tampilan tabel pada web ini--}}
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush

@endsection
