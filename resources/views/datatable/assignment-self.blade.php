<div class="table-responsive">
    <h4>Histori Pengajuan Cuti Pribadi</h4>
    <table class="table self">
        <thead>
            <tr>
                <th>NO</th>
                <th>Jenis Cuti</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Total Hari Kerja</th>
                <th>Tanggal Pengajuan</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@push('datatables-script')
<script type="text/javascript">
    $(function (){
        var table = $('.self').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('report.self') }}",
            columns:[
                {data: 'DT_RowIndex', name:'DT_RowIndex'},
                {data: 'jenis_cuti', name:'jenis_cuti'},
                {data: 'tgl_awal', name:'tgl_awal'},
                {data: 'tgl_akhir', name: 'tgl_akhir'},
                {data: 'total_cuti', name:'total_cuti'},
                {data: 'tgl_pengajuan', name:'tgl_pengajuan'},
                {data: 'tindakan',name: 'tindakan', orderable:false, searchable:false}
            ],
        });
    });
</script>
@endpush

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush