
<div id="dt" class="table-responsive">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/dataTable.js') }}"></script>
@endpush