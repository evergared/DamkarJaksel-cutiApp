@if(in_array('KASIE',auth()->user()->roles))
    @include('datatable.report.kasie-pjlp')


@elseif(in_array('KASUBAGTU',auth()->user()->roles))
    @include('datatable.report.tu-pjlp')

    
@elseif(in_array('KASUDIN',auth()->user()->roles))
    @include('datatable.report.kasudin-pjlp')

@elseif(in_array('PPK',auth()->user()->roles))
    @include('datatable.report.ppk-pjlp')

@elseif(auth()->user()->is_admin)
    @include('datatable.report.admin-pjlp')

    
@endif