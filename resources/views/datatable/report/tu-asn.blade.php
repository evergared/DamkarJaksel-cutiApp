<div id="dt-tu-asn">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/tu-asn.js') }}"></script>
@endpush