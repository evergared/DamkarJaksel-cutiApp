@if(in_array('KASIE',auth()->user()->roles))
    @include('datatable.report.kasie-asn')


@elseif(in_array('KASUBAGTU',auth()->user()->roles))
    @include('datatable.report.tu-asn')

    
@elseif(in_array('KASUDIN',auth()->user()->roles))
    @include('datatable.report.kasudin-asn')

@elseif(auth()->user()->is_admin)
    @include('datatable.report.admin-asn')

    
@endif