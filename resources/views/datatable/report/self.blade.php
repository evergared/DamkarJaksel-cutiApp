@if(auth()->user()->is_asn)
        <div id="dt-self-asn">
            <data-table :columns="columns" :ajax="ajax"></data-table>
        </div>
        @push('js')
            <script src="{{ asset('js/datatables/self-asn.js') }}"></script>
        @endpush

@elseif(auth()->user()->is_pjlp)
        <div id="dt-self-pjlp">
            <data-table :columns="columns" :ajax="ajax"></data-table>
        </div>
        @push('js')
            <script src="{{ asset('js/datatables/self-pjlp.js') }}"></script>
        @endpush

@endif