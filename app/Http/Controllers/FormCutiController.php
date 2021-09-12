<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Throwable;

class FormCutiController extends Controller
{

    public function index(Request $request)
    {
        // TODO : lakukan pengecekan untuk klasifikasi cuti

        //$this->validasiForm($request);
        // Via Auth()
        $this->submitCuti($request);
        
    }

    public function validasiForm(Request $request)
    {
        return $request->validate([
            "tmulai" => "required",
            "tselesai" => "required",
        ]);
    }

    public function submitCuti(Request $request)
    {
        error_log("Begin Submit cuti");

        //$this->validasiForm($request);

        $nrk = $request->input('nrk');
        $tglMulai = Date($request->input('tMulai'));
        $tglSelesai= Date($request->input('tSelesai'));
        $jenisCuti = $request->input('jCuti');
        $alasanCuti = $request->input('aCuti');

        error_log("NIP : ".$nrk);
        error_log("Mulai : ".$tglMulai);
        error_log("Selesai : ".$tglSelesai);

        // TODO : proses perhitungan hari
        // TODO : alasan cuti 


        // TODO : buat query untuk input, lalu tampilkan alert berhasil atau gagal
        $roles = $request -> session() -> get('roles');
        if(in_array("PJLP",$roles))
        {
            $asigment = DB::table('asigment_pjlp');
            $daftar = DB::table('daftar_cuti_pjlp');
            $tahunan = DB::table('cuti_tahunan_pjlp');
            error_log("User's role is PJLP");
        }
        elseif(in_array("ASN",$roles))
        {
            $asigment = DB::table('asigment_asn');
            $daftar = DB::table('daftar_cuti_asn');
            $tahunan = DB::table('cuti_tahunan_asn');
            error_log("User's role is ASN");
        }
        else
        {
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors('form_error','Proses gagal! Silahkan logout dan login kembali.');
                    error_log("User's role is unknown");
        }


        try
        {
            error_log("prepping for insert to daftar cuti");
            if($daftar !== null)
                error_log('Not null');
            $id = $daftar->insertGetId([
                'nip' => $nrk,
                'tgl_awal' => $tglMulai,
                'tgl_akhir' => $tglSelesai,
                'total_cuti' => 0,
                'tgl_pengajuan' => Date(today()),
                'jenis_cuti' => $jenisCuti
            ]);
            error_log("the id for entry is : ".$id);
            error_log("insert to daftar cuti has completed, check the database");
            
            error_log("prepping for insert to asignment cuti");
            $asigment -> insert([
                'no_cuti' => $id,
                'nip' => $nrk,
            ]);
            error_log("insert to asignment is completed, check the database");
            return redirect()->back()->with('form_success',"Form cuti berhasil diajukan! Cek Report Daftar Cuti untuk detail.");

        }

        catch(Throwable $e)
        {
            error_log("Error input database. User " . $request->user()->nip . " Time " . now() . "\nException : ".$e);
            report($e);
            return redirect()
            ->back()
            ->with('form_error','Insert database gagal! Cek data anda dan coba beberapa saat lagi atau hubungi admin.');
        }


        //return view('dashboard/form');
    }

    public function approveCuti (Request $request)
    {
        // update table asignment cuti
        // call approvalAction
    }

    public function disapproveCuti (Request $request)
    {
        // show modal confirmation
        // if yes, show modal input keterangan
        // if submit,
        // update table asignment cuti
        // call approvalAction
    }

    public function approvalAction (Request $request)
    {
        // check if everyone has vote

        // if all approve, substract cuti from relevant table
        // update the user's datatable able to show cuti application
        // notify user

        // if one or all disapprove,
        // update the user's datatable able to modify cuti form
        // notify user
    }

    public function cancelCuti (Request $request)
    {
        // get user's type (pjlp/asn)
        // delete cuti data from relevant asignment table
        // delete cuti data from daftar cuti
    }

    public function modifyCuti (Request $request)
    {
        // get user's type (pjlp/asn)
        // get cuti id
        // show cuti modify dialog with last input
        // if submit, update table daftar cuti
    }

    public function showCutiDetail (Request $request)
    {
        // get cuti id
        // show modal
    }

    public function getCutiApplication (Request $request)
    {
        // get cuti data (user nip/nrk, etc)
        // modify cuti application as necessary
        // show document as dom and able to print
    }
}
