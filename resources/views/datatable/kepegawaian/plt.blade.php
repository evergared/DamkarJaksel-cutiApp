<div id="dt-pegawai-plt">
    <data-table :columns="columns" ajax="{{route('list.plt')}}" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/pegawai-plt.js') }}"></script>
@endpush