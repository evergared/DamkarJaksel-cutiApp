
<div id="dt-admin-asn">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/admin-asn.js') }}"></script>
@endpush