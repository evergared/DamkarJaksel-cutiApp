
<div id="dt-plt-pjlp">
    <data-table :columns="columns" ajax="{{route('report.plt.pjlp')}}"></data-table>
</div>


@push('js')
    <script src="{{ asset('js/datatables/plt-pjlp.js') }}"></script>
@endpush