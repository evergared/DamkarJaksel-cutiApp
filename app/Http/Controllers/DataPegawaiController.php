<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPegawai as pegawai;

class DataPegawaiController extends Controller
{
    public function getIdPegawai()
    {
        return pegawai::select("select nip, nrk from data_pegawai");
    }
}
