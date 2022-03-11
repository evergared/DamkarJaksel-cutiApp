@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')


<div class="container-fluid mt--7">
    <div class="col-md-12 ">
        <div class='card bg-secondary shadow border-0 xl-4'>
            <div class="card-header">
                <div class='nav-wrapper d-flex flex-column flex-wrap align-items-end mx-3'>
                    <ul class="nav nav-fills nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#data-pegawai-master" role="tab" data-toggle="tab">Master Pegawai</a>
                        </li>    
                        <li class="nav-item">
                            <a class="nav-link" href="#data-pegawai-asn" role="tab" data-toggle="tab">Pegawai ASN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#data-pegawai-pjlp" role="tab" data-toggle="tab">Pegawai PJLP</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#data-jabatan" role="tab" data-toggle="tab">Jabatan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#data-penempatan" role="tab" data-toggle="tab">Penempatan</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link" href="#data-plt" role="tab" data-toggle="tab">PLT</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            

            <div class="card-body">
                <div class="tab-content mx-3">
                    <div role="tabpanel" class="tab-pane fade show active" id="data-pegawai-master">
                        <h2>Data Master Pegawai</h2>
                        @include('datatable.kepegawaian.pegawai-master')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="data-pegawai-asn">
                        <h2>List Pegawai ASN</h2>
                        @include('datatable.kepegawaian.pegawai-asn')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="data-pegawai-pjlp">
                        <h2>List Pegawai PJLP</h2>
                        @include('datatable.kepegawaian.pegawai-pjlp')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="data-jabatan">
                        <h2>List Jabatan</h2>
                        @include('datatable.kepegawaian.jabatan')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="data-penempatan">
                        <h2>List Penempatan</h2>
                        @include('datatable.kepegawaian.penempatan')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="data-plt">
                        <h2>Pelaksana Tugas</h2>
                        @include('datatable.kepegawaian.plt')
                    </div>
                </div>
            </div>
            
        </div>
    </div>



  @include('layouts.footers.nav')


</div>

@endsection

