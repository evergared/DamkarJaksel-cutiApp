<!-- <div class="table-responsive">
    <table class="table asn">
        <thead>
            <tr>
                <th>NO</th>
                <th>NIP</th>
                <th>NRK</th>
                <th>Nama</th>
                <th>Golongan</th>
                <th>Jabatan</th>
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
</div> -->

@if(in_array('KASIE',auth()->user()->roles))
    @include('datatable.report.kasie-asn')
@elseif(in_array('KASUBAGTU',auth()->user()->roles))

@elseif(in_array('KASUDIN',auth()->user()->roles))
@elseif(auth()->user()->is_admin)
    @include('datatable.report.admin-asn')
@endif