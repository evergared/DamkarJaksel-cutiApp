<div class="table-responsive">
<table class="table asn">
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
                <th>Persetujuan Anda</th>
                <th>Tindakan</th>
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
            ajax: "{{ route('report.asn') }}",
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
                {data: 'tgl_pengajuan', name:'tgl_pengajuan'},
                {data: 'p_kasie',name:'p_kasie',searchable:false},
                {data: 'tindakan',name: 'tindakan', orderable:false, searchable:false}

            ],
        });

        //var dtt = table.row( $(this).parents('tr')).data();

        $(".asn").on("click", "a.act_", function (e) {
        e.preventDefault();
        var magnifico = $(table.row($(this).closest('tr')).data()['tindakan']).html('a.act_');
        let thunderbolt = magnifico.data('galileo');
        let lightning = magnifico.data('figaro');

        //alert('approved : '+thunderbold);
        $('#approval').modal('show');
        $('')
        $('#aprv-form').submit(function(e){
           
            
        });
        });
        
    });

</script>

<script ></script>

<x-modal id="approval" title="Pilih tindakan untuk entri ini">
    <x-slot name="message">
        <form name="aprv-action">
            @csrf
            <span class="text-align-center">
                <input type="radio" name="op" id="op1" value="s"><label for='op1'>Setuju</label><br>
                <input type="radio" name="op" id="op2" value="u"><label for='op2'>Ubah</label><br>
                <input type="radio" name="op" id="op3" value="t"><label for='op3'>Tangguhkan</label><br>
                <input type="radio" name="op" id="op4" value="x"><label for='op4'>Tolak</label><br>
            </span>
                <input type="text" placeholder="(Opsional) Masukan alasan anda" name="alasan" id="alasan" disabled>
                <input type="hidden" name="thunderbolt">
                <input type="hidden" name="lightning">
        </form>
    </x-slot>
    <x-slot name="footer">
        <a class="btn btn-secondary my-2" data-toggle="modal" data-target="#approval">Batal</a>
        <button class="btn btn-primary my-2 text-white" name="approvalOk" type="submit">Terapkan</button>
    </x-slot>
</x-modal>
@endpush

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush