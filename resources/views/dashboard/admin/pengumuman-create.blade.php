@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')


<div class="container-fluid mt--7">

    <div class="col-md-12">
        <div class="card bg-secondary shadow border-0 xl-4">
            <div class="card-header">
                <h1>Buat Pengumuman Baru</h1>
                <small tabindex="-1">Buat post baru yang akan tampil sebagai pengumuman di halaman utama. Pengumuman yang dibuat tidak langsung tampil, pindah ke <a href="{{ route('pengumuman.list') }}"><u>daftar postingan</u></a> untuk mengatur tampilan halaman utama.</small>
            </div>
            <div class="card-body">

                @if(session()->has('addSuccess'))
                    <div class="alert alert-success alert-dismissible fade show focus" role="alert">
                        <span class="alert-inner--icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="alert-inner--text">{{ session('addSuccess') }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(session()->has('addFail'))
                    <div class="alert alert-warning alert-dismissible fade show focus" role="alert">
                        <span class="alert-inner--icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="alert-inner--text">{{ session('addFail') }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('add-pengumuman') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" id="judul" aria-describedby="judul-desc" required>
                        <small id="judul-desc" tabindex="-1">Judul hanya sebagai pembeda di database dan tidak akan ditampilkan saat diterapkan.</small>
                    </div>
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Pengumuman</label>
                        <textarea name="isi" class="form-control" id="pengumuman-editor"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a type="button" id="addPengumumanPreviewBtn" class="btn btn-secondary">Preview</a>
                </form>
                
            </div>
        </div>
    </div>

  @include('layouts.footers.nav')


</div>

@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor.create(document.querySelector('#pengumuman-editor'))
                     .then(e =>{
                            console.log(e);
                     })
                     .catch(r => {
                            console.error(r);
                     });   
    </script>
@endpush