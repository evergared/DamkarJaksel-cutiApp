<div id="dt-kasie-pjlp">
    <data-table :columns="columns" ajax="{{route('report.pjlp')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/kasie-pjlp.js') }}"></script>
@endpush