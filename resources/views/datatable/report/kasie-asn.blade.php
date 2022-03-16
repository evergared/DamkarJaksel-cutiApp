<div id="dt-kasie-asn">
    <data-table :columns="columns" ajax="{{route('report.asn')}}"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/kasie-asn.js') }}"></script>
@endpush