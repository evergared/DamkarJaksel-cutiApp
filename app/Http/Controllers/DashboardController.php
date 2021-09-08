<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


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
  }

  public static function getDashboard(Request $request)
  {
    $nip = $request->session()->get('nip');
    dd("anda login sebagai : ".$nip);
  }

  public function loadHome()
  {
    $this->checkAuth();
    return view('dashboard/home');
  }

  public function loadKepegawaian()
  {
    $this->checkAuth();
    $clearance = "3";

    return view('dashboard/kepegawaian')->with('clearance',$clearance);
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
