@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Mohon verifikasi E-mail anda</small>
                        </div>
                        <div class="text-center">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    E-mail verifikasi telah dikirim ke alamat e-mail anda.
                                </div>
                            @endif
                            
                            Sebelum melanjutkan, harap periksa akun E-mail anda untuk link verifikasi dari kami.<br><br>
                            
                            @if (Route::has('verification.resend'))
                                Jika anda tidak menerima E-mail dari kami, <a href="{{ route('verification.resend') }}">KLIK DISINI</a> untuk mengirimkan email verifikasi kembali.<br>
                                Atau <a href="{{ route('home') }}">KLIK DISINI</a> untuk melewati proses ini.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
