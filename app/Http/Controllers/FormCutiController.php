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

        $this->validasiForm($request);
        // Via Auth()
        $this->submitCuti($request);
        
    }

    public function validasiForm(Request $request)
    {
        return $request->validate([
            "tmulai" => "date|required",
            "tselesai" => "date|required",
        ]);
    }

    public function submitCuti(Request $request)
    {

        $nrk = $request->input('nrk');
        $tglMulai = $request->input('tMulai');
        $tglSelesai= $request->input('tSelesai');
        $jenisCuti = $request->input('jCuti');
        $alasanCuti = $request->input('aCuti');

        // TODO : proses perhitungan hari
        // TODO : alasan cuti 


        // TODO : buat query untuk input, lalu tampilkan alert berhasil atau gagal
        $roles = $request -> session() -> get('roles');
        if(in_array("PJLP",$roles))
        {
            $asigment = DB::table('asigment_pjlp');
            $daftar = DB::table('daftar_cuti_pjlp');
            $tahunan = DB::table('cuti_tahunan_pjlp');
        }
        elseif(in_array("ASN",$roles))
        {
            $asigment = DB::table('asigment_asn');
            $daftar = DB::table('daftar_cuti_asn');
            $tahunan = DB::table('cuti_tahunan_asn');
        }
        else
        {
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors('form_error','Proses gagal! Silahkan logout dan login kembali.');
        }

        try
        {
            $id = $daftar->insertGetId([
                'nip' => $nrk,
                'tgl_awal' => $tglMulai,
                'tgl_akhir' => $tglSelesai,
                'total_cuti' => $tglSelesai-$tglMulai,
                'tgl_pengajuan' => today(),
                'jenis_cuti' => $jenisCuti
            ]);

            $asigment -> insert([
                'no_cuti' => $id,
                'nip' => $nrk,
            ]);
        }

        catch(Throwable $e)
        {
            report("Error input database. User " . $request->user()->nip . " Time " . now() . "\nException : ".$e);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors('form_error','Database gagal! Silahkan coba beberapa saat lagi atau hubungi admin.');
        }


        return redirect()->back()->with('form_success','Permintaan cuti anda telah di submit. Silahkan cek Report Daftar Cuti untuk proses persetujuan.');
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
