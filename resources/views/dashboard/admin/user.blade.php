@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')


<div class="container-fluid mt--7">
    <div class="col-md-12 ">
        <div class='card bg-secondary shadow border-0 xl-4'>
            <div class="card-header">
                <div class='nav-wrapper d-flex flex-column align-items-end mx-3'>
                    <ul class="nav nav-fills nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#user-list" role="tab" data-toggle="tab">List User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#form-user" role="tab" data-toggle="tab">Tambah User</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            

            <div class="card-body">
                <div class="tab-content mx-3">
                    <div role="tabpanel" class="tab-pane fade show active" id="user-list">
                        <h2>List User</h2>
                        <div class="table-responsive" id="dt-user-list">
                            <data-table :columns="columns" :ajax="ajax"></data-table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="form-user">
                        <h2>Tambah User</h2>
                        <tambah-user></tambah-user>
                    </div>
                </div>
            </div>
            
        </div>
    </div>



  @include('layouts.footers.nav')


</div>

@endsection

@push('js')
    <script src="{{ asset('js/formUser.js') }}"></script>
    <script src="{{ asset('js/datatables/user-list.js') }}"></script>
@endpush