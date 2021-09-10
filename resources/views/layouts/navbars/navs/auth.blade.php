<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('home') }}">{{ __('Dashboard') }}</a>
        <nav class = "nav flex-column flex-sm-row d-none d-md-flex">
        <a class="h4 mr-3 text-white text-uppercase ml-lg-auto" title="Notifikasi" href="#"><i class="far fa-bell"></i></a>
        <a href="{{ route('logout') }}" class="h4 text-white text-uppercase" title="Logout" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
        </nav>
    </div>
</nav>