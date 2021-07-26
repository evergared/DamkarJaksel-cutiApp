<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{

  public function checkAuth()
  {
    if(Auth::check())
    {
      return;
    }
    kickUser();
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
