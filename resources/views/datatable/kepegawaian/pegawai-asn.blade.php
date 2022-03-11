<div id="dt-pegawai-asn">
    <data-table :columns="columns" ajax="{{route('list.asn')}}" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/pegawai-asn.js') }}"></script>
@endpush