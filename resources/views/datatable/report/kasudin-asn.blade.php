<div id="dt-kasudin-asn">
    <data-table :columns="columns" ajax="{{route('report.asn')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/kasudin-asn.js') }}"></script>
@endpush