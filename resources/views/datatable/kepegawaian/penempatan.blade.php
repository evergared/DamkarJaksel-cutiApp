<div id="dt-pegawai-penempatan">
    <data-table :columns="columns" ajax="{{route('list.penempatan')}}" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/pegawai-penempatan.js') }}"></script>
@endpush