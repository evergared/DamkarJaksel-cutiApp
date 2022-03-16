@if(auth()->user()->is_kasie)
    @if(auth()->user()->is_ppk)
        @include('datatable.report.kasieppk-pjlp')
    @else
        @include('datatable.report.kasie-pjlp')
    @endif


@elseif(auth()->user()->is_kasubag_tu)
    @include('datatable.report.tu-pjlp')

    
@elseif(auth()->user()->is_kasudin)
    @include('datatable.report.kasudin-pjlp')

@elseif(auth()->user()->is_ppk)
    @include('datatable.report.ppk-pjlp')

@elseif(auth()->user()->is_admin)
    @include('datatable.report.admin-pjlp')

    
@endif