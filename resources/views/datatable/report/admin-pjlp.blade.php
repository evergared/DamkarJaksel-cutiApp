
<div id="dt-admin-pjlp">
    <data-table :columns="columns" :ajax="ajax" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/admin-pjlp.js') }}"></script>
@endpush