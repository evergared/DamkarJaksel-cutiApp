<div id="dt-kasieppk-pjlp">
    <data-table :columns="columns" ajax="{{route('report.pjlp')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/kasieppk-pjlp.js') }}"></script>
@endpush