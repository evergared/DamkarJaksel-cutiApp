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
                <th>Alasan</th>
                <th>Tanggal Pengajuan</th>
                <th>Persetujuan Anda</th>
                <th>Alasan Tindakan</th>
                <th>Tindakan</th>
                
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
                {data: 'p_ppk',name:'p_ppk',searchable:false},
                {data: 'k_ppk', name:'k_ppk',searchable:false},
                {data: 'tindakan',name: 'tindakan', orderable:false, searchable:false}

            ],
        });

        //var dtt = table.row( $(this).parents('tr')).data();

        $(".pjlp").on("click", "a.act_", function (e) {
            e.preventDefault();
            var magnifico = $(table.row($(this).closest('tr')).data()['tindakan']).html('a.act_'); {{--ambil atribut data dari tombol tindakan--}}
            let thunderbolt = magnifico.data('galileo'); {{--NIP--}}
            let lightning = magnifico.data('figaro'); {{--No_cuti--}}

            $('#thunderbolt').val(thunderbolt);
            $('#lightning').val(lightning);

            let z = $(table.row($(this).closest('tr')).data()['p_kasie']).html('strong').prop('id');
                console.log('op2 is '+$('#op2').prop('checked'));

            switch(z)
            {
                case 's': $('input#op1').prop('checked',true);console.log('op1');break;
                case 'u': $('input#op2').prop('checked',true);console.log('op2');break;
                case 't': $('input#op3').prop('checked',true);console.log('op3');break;
                case 'x': $('input#op4').prop('checked',true);console.log('op4');break;
                case 'bc': $('input.op').prop('checked',false);console.log('op dead');break;
            }

            if($('input#op2').prop('checked') == true || $('input#op3').prop('checked') == true || $('input#op4').prop('checked') == true)
            {
                $('input#alasan').prop('disabled',false);
                $('input#alasan').val($(table.row($(this).closest('tr')).data()['k_kasie']).text());
            }
            else
            {
                $('input#alasan').prop('value',"");
                $('input#alasan').prop('disabled',true);

            }


            $('input.op').change(function(){
                if($('input#op1').prop('checked') == true)
                    $('input#alasan').prop('disabled',true);
                else
                    $('input#alasan').prop('disabled',false);
            });

            $('#approval').modal('show');

        });
        
    });

</script>

<script ></script>

<x-modal id="approval" title="Pilih tindakan untuk entri ini">
    <x-slot name="message">
        <form name="aprv-action" autocomplete="off" action="{{ route('report.pjlp.approval')}}">
            @csrf
            <span class="text-align-center">
                <input type="radio" class="op" name="op" id="op1" value="s"><label for='op1'>Setuju</label><br>
                <input type="radio" class="op" name="op" id="op2" value="u"><label for='op2'>Ubah</label><br>
                <input type="radio" class="op" name="op" id="op3" value="t"><label for='op3'>Tangguhkan</label><br>
                <input type="radio" class="op" name="op" id="op4" value="x"><label for='op4'>Tolak</label><br>
            </span>
                <input type="text" placeholder="(Opsional) Masukan alasan anda" name="alasan" id="alasan"  disabled>
                <input type="hidden" name="thunderbolt" id="thunderbolt">
                <input type="hidden" name="lightning" id="lightning">
        
    </x-slot>
    <x-slot name="footer">
        <a class="btn btn-secondary my-2" data-toggle="modal" data-target="#approval">Batal</a>
        <button class="btn btn-primary my-2 text-white" name="approvalOk" type="submit">Terapkan</button>
    </form>
    </x-slot>
</x-modal>
@endpush

@push('datatables')
<link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
@endpush