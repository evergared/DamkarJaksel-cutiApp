<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{

  public function checkAuth()
  {
    if(!Auth::check())
    {
      $this->kickUser();
    }
  }

  public function kickUser()
  {
    abort(403);
    redirect('/home');
  }

  public function getDashboard($jabatan)
  {
    $list = DB::get('tabel_jabatan');

    //if($jabatan)
  }

  public function loadHome()
  {
    return view('dashboard/home');
  }

  public function loadKepegawaian()
  {
    return view('dashboard/kepegawaian');
  }

  public function loadReport()
  {
    return view('dashboard/report');
  }

  public function loadForm()
  {
    return view('dashboard/form');
  }
}

?>
