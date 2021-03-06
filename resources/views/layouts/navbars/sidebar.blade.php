<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
  <div class="container-fluid">
    <!-- Toggler untuk saat web menampilkan halaman narrow melebihi sidebar / saat web ditampilkan untuk android -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand pt-0" href="{{ route('home') }}">
        <strong>Web Cuti Pegawai</strong>
    </a>

    {{-- hilangkan clear-fix jika right side button dipakai --}}
    <div class="clear-fix"></div>

    {{--
    <!-- Right Side button -->
    <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                    <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                    </span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="ni ni-single-02"></i>
                    <span>{{ __('My profile') }}</span>
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    <i class="ni ni-user-run"></i>
                    <span>{{ __('Logout') }}</span>
                </a>
            </div>
        </li>
    </ul>
    --}}

<!-- Menu Sidebar -->
    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
      <!-- Header menu saat di Collapse -->
      <div class="navbar-collapse-header d-md-none">
          <div class="row">
              <div class="col-6 collapse-brand">
                  <a href="{{ route('home') }}">
                      <strong>{{ __('Aplikasi Cuti')}}</strong>
                  </a>
              </div>
              <!-- Tombol untuk menutup Collapse -->
              <div class="col-6 collapse-close">
                  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                      <span></span>
                      <span></span>
                  </button>
              </div>
          </div>
      </div>

      <small>{{ __('Selamat Datang!')}} <br> {{ auth()->user()->data['nama'] }}</small>
      <hr class = "my-2">
      Navigasi
      <ul class="navbar-nav ">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="ni ni-shop text-blue"></i><span class="nav-link-text">{{ __('Home')}}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('report') }}"><i class="ni ni-calendar-grid-58 text-red"></i><span class="nav-link-text">{{ __('Report Daftar Cuti')}}</span></a></li>
        @if(auth()->user()->is_plt)
        <li class="nav-item"><a class="nav-link" href="{{ route('plt') }}"><i class="ni ni-calendar-grid-58 text-red"></i><span class="nav-link-text">{{ __('Report Daftar Cuti (PLT)')}}</span></a></li>
        @endif
        <li class="nav-item"><a class="nav-link" href="{{ route('form') }}"><i class="ni ni-single-copy-04 text-gray"></i><span class="nav-link-text">{{ __('Form Pengajuan Cuti')}}</span></a></li>
      </ul>
      <hr class = "my-2">
      @if(auth()->user()->is_admin)
      Admin
      <ul class="navbar-nav ">
        <li class="nav-item">
            <a class="nav-link active collapsed"  data-target="#navbar-pengumuman" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-pengumuman"><i class="fa fa-newspaper text-blue"></i><span class="nav-link-text">{{ __('Pengumuman')}}</span></a>
            <div class="collapse" id="navbar-pengumuman">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item"><a class="nav-link" href="{{ route('pengumuman.create')}}">Buat Posting Baru</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pengumuman.list')}}">Daftar Posting</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('kepegawaian') }}"><i class="ni ni-briefcase-24 text-orange"></i><span class="nav-link-text">{{ __('Kepegawaian')}}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('pengguna') }}"><i class="fas fa-user text-success"></i><span class="nav-link-text">{{ __('User')}}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('calendar') }}"><i class="fas fa-calendar text-blue"></i><span class="nav-link-text">{{ __('Kalender')}}</span></a></li>
    </ul>
      @endif
      <a class="nav-link text-center" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><strong class="nav-link-text">{{ __('Logout')}}</strong></a>
      <!-- <a class="nav-link" href="#"><strong class="nav-link-text">{{ __('Petunjuk Penggunaan')}}</strong></a> -->

    </div>
  </div>
</nav>
