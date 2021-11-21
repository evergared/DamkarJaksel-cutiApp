<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Throwable;

use App\Events\CutiSubmitEvent;

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

        $nip = $request->input('nip');
        $tglMulai = Date($request->input('start'));
        $tglSelesai= Date($request->input('end'));
        $jenisCuti = $request->input('jenisCuti');
        $alamatCuti = $request->input('alamat');
        $alasanCuti = $request->input('alasan');
        $listTanggal = implode('||',$request->input('tanggal'));
        $lama = $request->input('lama');

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
            // return redirect()
            //         ->back()
            //         ->withInput()
            //         ->withErrors('form_error','Proses gagal! Silahkan logout dan login kembali.');

            return "fail_submit_role_not_found";
        }


        try
        {

            $id = $daftar->insertGetId([
                'nip' => $nip,
                'tgl_awal' => $tglMulai,
                'tgl_akhir' => $tglSelesai,
                'total_cuti' => $lama,
                'tgl_pengajuan' => Date(today()),
                'jenis_cuti' => $jenisCuti,
                'alasan' => $alasanCuti,
                'alamat' => $alamatCuti,
                'tanggal' => $listTanggal
            ]);
            
            $asigment -> insert([
                'no_cuti' => $id
            ]);

            CutiSubmitEvent::dispatch($nip,$id);

            //return response('form_success',"Form cuti berhasil diajukan! Cek Report Daftar Cuti untuk detail.");
            return "success_submit";
        }

        catch(Throwable $e)
        {
            error_log("Submit Cuti Gagal : ".$e);
            report("Error input database. User : " . $request->user()->nip . " Time " . now() . "\nException : ".$e);
            return "fail_submit_try_caught";
            //return response('form_error','Insert database gagal! Cek data anda dan coba beberapa saat lagi atau hubungi admin.');
        }


    }

    public function approvalStatus(Request $request)
    {
        // get approval status and return the value for radio button
        try{
            $nip = $request->nip;
            $no_cuti = $request->no_cuti;

            $check = DB::table('data_pegawai')->where('nip','=',$nip)->get('golongan')->first();

            if($check === 'PJLP')
            {
                $asigment = DB::table('asigment_pjlp');
            }
            else
            {
                $asigment = DB::table('asigment_asn');
            }

            $asigment = $asigment->where('no_cuti','=',$no_cuti);

            if(in_array('KASIE',Auth::user()->roles))
            {
                $data = $asigment->get('kasie','ket_kasie')->first();
            }
            elseif(in_array('KASUBAGTU',Auth::user()->roles))
            {
                $data = $asigment->get('kasubagtu','ket_tu')->first();
            }
            elseif(in_array('PPK',Auth::user()->roles) && $check === 'PJLP')
            {
                $data = $asigment->get('ppk','ket_ppk')->first();
            }
            else
            {
                return 'approval_fetch_fail';
            }

             return ['status' => $data[0], 'keterangan' => $data[1]];

        }

        catch(Throwable $e){
            error_log('Fetch data approval on '.$nip.' no cuti '.$no_cuti.' error : '.$e);
            report('Fetch data approval on '.$nip.' no cuti '.$no_cuti.' error : '.$e);
            return 'approval_fetch_try_caught';
        }
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

        try
        {

            $nip = $request->input('nip');
            $no_cuti = $request->input('no_cuti');

            $check = DB::table('data_pegawai')->where('nip',$nip)->get('golongan')->first();
    
            if($check === "PJLP")
            {
                $assignment = DB::table('asigment_pjlp');
                $daftar = DB::table('daftar_cuti_pjlp');
            }
            else
            {
                $assignment = DB::table('asigment_asn');
                $daftar = DB::table('daftar_cuti_asn');
            }

            if(in_array('KASIE',Auth::user()->roles))
            {
                $assignment->where('no_cuti',$no_cuti)->update(['kasie'=>$request->input('status'),'ket_kasie'=>$request->input('keterangan')]);
            }
            elseif(in_array('KASUBAGTU',Auth::user()->roles))
            {
                $assignment->where('no_cuti',$no_cuti)->update(['kasubagtu'=>$request->input('status'),'ket_tu'=>$request->input('keterangan')]);
            }
            elseif(in_array('PPK',Auth::user()->roles) && $check === 'PJLP')
            {
                $assignment->where('no_cuti',$no_cuti)->update(['ppk'=>$request->input('status'),'ket_ppk'=>$request->input('keterangan')]);
            }
            // TODO : jika semua sudah approve, tembak event

            return 'approval_update_success';
        }

        catch(Throwable $e)
        {
            error_log('Approval update error on '.$nip.' no cuti '.$no_cuti.' error : '.$e);
            report('Approval update error on '.$nip.' no cuti '.$no_cuti.' error : '.$e);
            return 'approval_update_try_caught';
        }

    }

    public function cancelCuti (Request $request)
    {
        // get user's type (pjlp/asn)
        // delete cuti data from relevant asignment table
        // delete cuti data from daftar cuti

        try
        {

            $nip = $request->input('nip');
            $no_cuti = $request->input('no_cuti');

            error_log("request : ".$request->nip);
            error_log("test delete axios : Nip ".$nip." no cuti ".$no_cuti);

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

            $a->where('no_cuti','=',$no_cuti)->delete();

            $d->delete($no_cuti);

            return "delete_success";
        }
        
        catch(Throwable $e)
        {
            error_log("Error Delete Cuti : ".$nip." No cuti : ".$no_cuti." Exception : ".$e);
            report("Error Delete Cuti : ".$nip." No cuti : ".$no_cuti." Exception : ".$e);
            return "delete_fail";
        }

        
    }

    public function modifyCuti (Request $request)
    {
        // get user's type (pjlp/asn)
        // get cuti id
        // show cuti modify dialog with last input
        // if submit, update table daftar cuti

        try
        {

            $check = DB::table('data_pegawai')->where('nip','=',$request->input('nip'))->get()->first();

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

        

            $no_cuti = $request->input('no_cuti');
            $start = $request->input('start');
            $end = $request->input('end');
            $lama = $request->input('lama');
            $tanggal = implode('||',$request->input('tanggal'));
            $jenis = $request->input('jenisCuti');
            $alamat = $request->input('alamat');
            $alasan = $request->input('alasan');

            $d->where('id','=',$no_cuti)->update([

                'tgl_awal' => $start,
                'tgl_akhir' => $end,
                'total_cuti' => $lama,
                'jenis_cuti' => $jenis,
                'alamat' => $alamat,
                'alasan' => $alasan,
                'tanggal' => $tanggal

            ]);

            return "success_update";

            // return redirect()
            //     ->back()
            //     ->with('report_success','Cuti berhasil dihapus!');
        }
        
        catch(Throwable $e)
        {
            error_log("Error Delete Cuti : ".$request->input('nip')." No cuti : ".$no_cuti." Exception : ".$e);
            report("Error Delete Cuti : ".$request->input('nip')." No cuti : ".$no_cuti." Exception : ".$e);
            return "fail_update_try_caught";
        }

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
