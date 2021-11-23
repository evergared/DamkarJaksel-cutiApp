
<div id="dt-admin-pjlp" class="table-responsive">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/admin-pjlp.js') }}"></script>
@endpush