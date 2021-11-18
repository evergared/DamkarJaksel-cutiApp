
<div id="dt">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/dataTable.js') }}"></script>
@endpush