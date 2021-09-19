@if(auth()->user()->is_admin)
    @include('datatable.report.admin-pjlp')
@endif