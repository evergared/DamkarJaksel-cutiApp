<div id="dt-pegawai-master">
    <data-table :columns="columns" ajax="{{route('list.master')}}" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/pegawai-master.js') }}"></script>
@endpush