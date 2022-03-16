<div id="dt-pegawai-pjlp">
    <data-table :columns="columns" ajax="{{route('list.pjlp')}}" :buttons="buttons"></data-table>
</div>

@push('js')
    <script src="{{ asset('js/datatables/pegawai-pjlp.js') }}"></script>
@endpush