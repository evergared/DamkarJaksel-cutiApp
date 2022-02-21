<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;

class PegawaiController extends Controller
{

    public function addPegawai(Request $request)
    {
        try{

            $nip = $request->input('nip');
            $nrk = $request->input('nrk');
            $nama = $request->input('nama');
            $jabatan = $request->input('jabatan');
            $golongan = $request->input('golongan');
            $penempatan = $request->input('penempatan');
            $kompi = $request->input('kompi');
            $pendidikan = $request->input('pendidikan');
            $t_masuk = $request->input('t_masuk');

            /**
             * Jabatan : 
             * - pjlp
             * - karu
             * - katon
             * - kasie
             * - 
             */

            if($jabatan->count > 1)
                $jbtn = implode('|',$jabatan);
            else
                $jbtn = $jabatan[0];

            

        }
        catch(Throwable $e)
        {
            error_log("Add pegawai error : "+$e);
            report("Add pegawai error : "+$e);
            return "fail_add_pegawai_try_caught";
        }
    }

    public function modifyPegawai(Request $request)
    {

    }
}

?>