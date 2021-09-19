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
                <th>Kasie</th>
                <th>Atasan</th>
                <th>Pendidikan</th>
                <th>Kode Penempatan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@push('datatables-script')
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
                {data: 'kasie', name:'kasie'},
                {data: 'atasan', name:'atasan'},
                {data: 'pendidikan',name: 'pendidikan'},
                {data: 'kode_penempatan', name:'kode_penempatan'},
                {data: 'keterangan', name:'keterangan'}
            ]

        });
    });
</script>
@endpush

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush
