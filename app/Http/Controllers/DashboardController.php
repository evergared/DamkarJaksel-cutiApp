<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

  public function checkAuth()
  {
    if(Auth::check())
    {
      return;
    }
    $this->kickUser();
  }

  public function kickUser()
  {
    abort(403);
  }

  public function getDashboard($jabatan)
  {
    $list = DB::get('tabel_jabatan');

    //if($jabatan)
  }

  public function loadHome()
  {
    $this->checkAuth();
    return view('dashboard/home');
  }

  public function loadKepegawaian()
  {
    $this->checkAuth();
    return view('dashboard/kepegawaian');
  }

  public function loadReport()
  {
    $this->checkAuth();
    return view('dashboard/report');
  }

  public function loadForm()
  {
    $this->checkAuth();
    return view('dashboard/form');
  }
}

?>
