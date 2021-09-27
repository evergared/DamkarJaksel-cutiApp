<div class="table-responsive">
<table class="table pjlp">
        <thead>
            <tr>
                <th>NO</th>
                <th>NIP</th>
                <th>NRK</th>
                <th>Nama</th>
                <th>Jenis Cuti</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Total Hari Kerja</th>
                <th>Tanggal Pengajuan</th>
                <th>Penempatan</th>
                <th>Persetujuan Kasie Sektor</th>
                <th>Keterangan Kasie</th>
                <th>Persetujuan Kasubag TU</th>
                <th>Keterangan Kasubag TU</th>
                
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@push('datatables-script')
<script type="text/javascript">
    $(function (){
        var table = $('.pjlp').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('report.pjlp') }}",
            dom : 'Bfrtip',
            columns:[
                {data: 'DT_RowIndex', name:'DT_RowIndex'},
                {data: 'nip', name:'nip'},
                {data: 'nrk', name:'nrk'},
                {data: 'nama', name:'nama'},
                {data: 'jenis_cuti', name:'jenis_cuti'},
                {data: 'tgl_awal', name:'tgl_awal'},
                {data: 'tgl_akhir', name: 'tgl_akhir'},
                {data: 'total_cuti', name:'total_cuti'},
                {data: 'alasan', name:'alasan',searchable:false},
                {data: 'tgl_pengajuan', name:'tgl_pengajuan'},
                {data: 'penempatan',name:'penempatan',searchable:false},
                {data: 'p_kasie',name:'p_kasie',searchable:false},
                {data: 'k_kasie', name:'k_kasie',searchable:false},
                {data: 'p_tu',name:'p_tu',searchable:false},
                {data: 'k_tu', name:'k_tu',searchable:false},

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