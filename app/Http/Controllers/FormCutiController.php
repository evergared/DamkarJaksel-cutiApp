<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\View\Components\Modal;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

class FormCutiController extends Controller
{

    public function submitCutiPegawai(Request $request)
    {

        $nrk = $request->input('nrk');
        $tglMulai = $request->input('tMulai');
        $tglSelesai= $request->input('tSelesai');
        $jenisCuti = $request->input('jCuti');
        $alasanCuti = $request->input('aCuti');

        $message = "NRK : " . $nrk . "Tanggal : " . $tglMulai . " hingga " . $tglSelesai . "Jenis Cuti : " . $jenisCuti . "Dengan Alasan : " . $alasanCuti;

        // TODO : buat query untuk input, lalu tampilkan alert berhasil atau gagal
        
        return view('dashboard/form');
    }
}
