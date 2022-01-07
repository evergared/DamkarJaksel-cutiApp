<div id="dt-kasudin-pjlp">
    <data-table :columns="columns" ajax="{{route('report.pjlp')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/kasudin-pjlp.js') }}"></script>
@endpush