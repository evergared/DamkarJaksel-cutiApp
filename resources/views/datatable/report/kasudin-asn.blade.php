<div id="dt-kasudin-asn" class="table-responsive">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/kasudin-asn.js') }}"></script>
@endpush