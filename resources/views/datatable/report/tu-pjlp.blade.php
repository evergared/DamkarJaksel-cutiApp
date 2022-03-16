<div id="dt-tu-pjlp">
    <data-table :columns="columns" ajax="{{route('report.pjlp')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/tu-pjlp.js') }}"></script>
@endpush