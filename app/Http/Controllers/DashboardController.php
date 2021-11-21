<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

  // TODO : Ganti fungsi CheckAuth dengan middleware

  public function checkAuth()
  {
    if(!Auth::check())
    {
      $this->kickUser();
    }
  }

  public function kickUser()
  {
    //abort(403);
  }

  public static function getDashboard(Request $request)
  {
     $nip = $request->session()->get('roles');
     route("home");
  }

  public function loadHome(Request $request)
  {
    $this->checkAuth();
    return view('dashboard/home');
  }

  public function loadKepegawaian(Request $request)
  {
    $this->checkAuth();
    return view('dashboard/kepegawaian');
  }

  public function loadReport(Request $request)
  {
    $this->checkAuth();
    return view('dashboard/report');
  }

  public function loadCalendar()
  {
    return view('dashboard/admin/calendar');
  }

  public function loadForm(Request $request)
  {
    $cuti = [
      "Tahunan",
      // "Melahirkan",
      // "Sakit",
      // "Negara",
      // "Besar",
      // "Penting"
    ];

    return view('dashboard/form')->with("dd_jcuti",$cuti);
  }
}

?>
