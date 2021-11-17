<div class="table-responsive">
    <h4>Data Cuti Keseluruhan Pegawai ASN</h4>
    <table class="table asn">
        <thead>
            <tr>
                <th>NO</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Penempatan</th>
                <th>Jenis Cuti</th>
                <th>Alasan</th>
                <th>Alamat</th>
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

<!-- <a id="testis" href='#'>TESTIS</a>

<div  class="modal fade" tabindex="0" id="form-cuti">
    <div class="col-lg-8 modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-body" id="app">
            
        <form-cuti id="cuti-updater" nip="22223333"></form-cuti>
            
      </div>
            
        </div>
    </div>
</div> -->

@push('datatables-script')
<script type="text/javascript">
    // import 
    $(function (){
        var table = $('.asn').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('report.asn') }}",
            dom : 'Bfrtip',
            columns:[
                {data: 'DT_RowIndex', name:'DT_RowIndex'},
                {data: 'nip', name:'nip'},
                {data: 'nama', name:'nama'},
                {data: 'penempatan', name:'penempatan'},
                {data: 'jenis_cuti', name:'jenis_cuti'},
                {data: 'alasan', name:'alasan'},
                {data: 'alamat', name:'alamat'},
                {data: 'tgl_awal', name:'tgl_awal'},
                {data: 'tgl_akhir', name: 'tgl_akhir'},
                {data: 'total_cuti', name:'total_cuti'},
                {data: 'tgl_pengajuan', name:'tgl_pengajuan'},
                {data: 'tindakan',name: 'tindakan', orderable:false, searchable:false}

            ],
        });
    });

    function updateModal(targetData){
        var nip = targetData.getAttribute('data-nip');
        alert("test modlue : "+$('#cuti-updater').prop('nip'));
         //$('#testis').attr('class','btn btn-sm');
        //$('form-cuti').addAttribute('nip',targetData.getAttribute('data-nip'));
        //alert($('#cuti').attr('nip'));
         $('#form-cuti').modal('show');
    }
</script>
@endpush

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush