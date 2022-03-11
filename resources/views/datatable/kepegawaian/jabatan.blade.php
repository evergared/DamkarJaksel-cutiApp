<div id="dt-pegawai-jabatan">
    <data-table :columns="columns" ajax="{{route('list.jabatan')}}" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/pegawai-jabatan.js') }}"></script>
@endpush