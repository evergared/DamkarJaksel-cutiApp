@extends('layouts.app')

@section('content')

<div class="table-responsive" id="dt-admin-pjlp">
    <data-table :columns="columns" :ajax="ajax"></data-table>
</div>


@endsection


@push('js')
    <script src="{{ asset('js/datatables/admin-pjlp.js') }}"></script>
@endpush

<!-- <script type="javascript/text">
    import form-cuti from ''
</script> -->