<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\View\Components\Modal;
use Illuminate\Http\Request;

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

        $modal = new Modal("Test", $message);
        $modal->render();
    }
}
