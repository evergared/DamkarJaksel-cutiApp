@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')

<div class="container-fluid mt--7">

    <div class="col-md-12">
        <div class="card bg-secondary shadow border-0 xl-4">
            <div class="card-body">

                <div class="card">
                    <div class="card-body">
                        <div class="text-center align-center"><h3>Tidak ada pengumuman yang ditampilkan</h3></div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body">
                        <b>List Pengumuman</b>
                        <hr>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4" id="pengumuman-preview">
        <div class="card bg-secondary shadow border-0 xl-4">
            <div class="card-header">
                <span><b><i><small class="muted">PREVIEW</small></i></b></span>
                <button type="button" class="close" data-dismiss="#pengumuman-preview" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>

@include('layouts.footers.nav')

</div>
@endsection
