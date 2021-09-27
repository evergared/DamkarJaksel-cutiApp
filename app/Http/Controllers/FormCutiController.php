<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $nrk = $request->input('nrk');
        $tglMulai = Date($request->input('tMulai'));
        $tglSelesai= Date($request->input('tSelesai'));
        $jenisCuti = $request->input('jCuti');
        $alasanCuti = $request->input('aCuti');

        // TODO : proses perhitungan hari
        // TODO : alasan cuti 



        if(Auth::user()->is_pjlp)
        {
            $asigment = DB::table('asigment_pjlp');
            $daftar = DB::table('daftar_cuti_pjlp');
            $tahunan = DB::table('cuti_tahunan_pjlp');
        }
        elseif(Auth::user()->is_asn)
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
                'total_cuti' => 0,
                'tgl_pengajuan' => Date(today()),
                'jenis_cuti' => $jenisCuti,
                'alasan' => $alasanCuti
            ]);
            
            $asigment -> insert([
                'no_cuti' => $id
            ]);

            return redirect()->back()->with('form_success',"Form cuti berhasil diajukan! Cek Report Daftar Cuti untuk detail.");

        }

        catch(Throwable $e)
        {
            report("Error input database. User : " . $request->user()->nip . " Time " . now() . "\nException : ".$e);
            return redirect()
            ->back()
            ->with('form_error','Insert database gagal! Cek data anda dan coba beberapa saat lagi atau hubungi admin.');
        }


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


        $nip = $request->input('thunderbolt');
        $no_cuti = $request->input('lightning');

        if(DB::table('data_pegawai')->where('nip',$nip)->get('golongan')->first() === "PJLP")
        {
            $assignment = DB::table('asigment_pjlp');
            $daftar = DB::table('daftar_cuti_pjlp');
        }
        else
        {
            $assignment = DB::table('asigment_asn');
            $daftar = DB::table('daftar_cuti_asn');
        }


        try
        {
            if(in_array('KASIE',Auth::user()->roles))
            {
                $assignment->where('no_cuti',$no_cuti)->update(['kasie'=>$request->input('op'),'ket_kasie'=>$request->input('alasan')]);
            }
            elseif(in_array('KASUBAGTU',Auth::user()->roles))
            {
                $assignment->where('no_cuti',$no_cuti)->update(['kasubagtu'=>$request->input('op'),'ket_tu'=>$request->input('alasan')]);
            }
            elseif(in_array('PPK',Auth::user()->roles))
            {
                $assignment->where('no_cuti',$no_cuti)->update(['ppk'=>$request->input('op'),'ket_ppk'=>$request->input('alasan')]);
            }
            // TODO : jika semua sudah approve, tembak event

            return redirect()->back();
        }

        catch(Throwable $e)
        {
            report($e);
            return redirect()->back();
        }
        return redirect()->back();

    }

    public function cancelCuti (Request $request,$nip,$no_cuti)
    {
        // get user's type (pjlp/asn)
        // delete cuti data from relevant asignment table
        // delete cuti data from daftar cuti

        $check = DB::table('data_pegawai')->where('nip','=',$nip)->get()->first();

        if($check->golongan === "PJLP")
        {
            $d = DB::table('daftar_cuti_pjlp');
            $a = DB::table('asigment_pjlp');
        }
        else
        {
            $d = DB::table('daftar_cuti_asn');
            $a = DB::table('asigment_asn');
        }

        try
        {
            $a->where('no_cuti','=',$no_cuti)->delete();

            $d->delete($no_cuti);

            return redirect()
                ->back()
                ->with('report_success','Cuti berhasil dihapus!');
        }
        
        catch(Throwable $e)
        {
            report("Error Delete Cuti : ".$nip." No cuti : ".$no_cuti." Exception : ".$e);
            return redirect()
                ->back()
                ->withErrors('report_error','Cuti gagal dihapus!');
        }

        
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
