<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

  public function loadHome(Request $request)
  {
    return view('dashboard/home');
  }

  public function loadKepegawaian(Request $request)
  {
    return view('dashboard/admin/pegawai');
  }

  public function loadReport(Request $request)
  {
    return view('dashboard/report');
  }

  public function loadPltReport(Request $request)
  {
    return view('dashboard/plt');
  }

  public function loadCalendar()
  {
    return view('dashboard/admin/calendar');
  }

  public function loadUser()
  {
    return view('dashboard/admin/user');
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
