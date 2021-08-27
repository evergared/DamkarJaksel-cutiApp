@extends('layouts.app')

@section('content')

<div>

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
    </table>
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

@endsection

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush