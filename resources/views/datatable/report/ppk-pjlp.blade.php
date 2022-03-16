<div id="dt-ppk-pjlp">
    <data-table :columns="columns" ajax="{{route('report.pjlp')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/ppk-pjlp.js') }}"></script>
@endpush