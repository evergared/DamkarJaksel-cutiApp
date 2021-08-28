@extends('layouts.app')

@section('content')

<div>

<div >
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

    @include('layouts.footers.nav')
</div>

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

@endsection

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush