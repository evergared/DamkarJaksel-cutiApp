
<div id="dt-plt-asn">
    <data-table :columns="columns" ajax="{{route('report.plt.asn')}}"></data-table>
</div>


@push('js')
    <script src="{{ asset('js/datatables/plt-asn.js') }}"></script>
@endpush