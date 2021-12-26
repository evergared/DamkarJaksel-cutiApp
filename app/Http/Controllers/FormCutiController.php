<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Options;
use Carbon\Carbon;
use Throwable;

use App\Events\CutiSubmitEvent;
use App\Events\CutiPrintEvent;

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

            $flag = $request->input('flag');
            error_log("flag : ".$flag);

            $field = 'test';
            if($flag === 2)
                $field = 'na2';
            else if($flag === 1)
                $field = 'na1';
            else if($flag === 0)
                $field = 'na';

            error_log('field : '.$field);

            $id = $daftar->insertGetId([
                'nip' => $nip,
                'tgl_awal' => $tglMulai,
                'tgl_akhir' => $tglSelesai,
                'total_cuti' => $lama,
                $field => $lama,
                'tgl_pengajuan' => Date(today()),
                'jenis_cuti' => $jenisCuti,
                'alasan' => $alasanCuti,
                'alamat' => $alamatCuti,
                'tanggal' => $listTanggal
            ]);

            // check jika user staff TU
            if(Auth::user()->atasan === 11)
                $asigment -> insert([
                    'no_cuti' => $id,
                    'kasie' =>'s'
                ]);

            // untuk pegawai non TU
            else
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
            $nip = $request->input('nip');
            $no_cuti = $request->input('no_cuti');

            // $nip = $test1;
            // $no_cuti = $test2;


            $check = DB::table('data_pegawai')->where('nip','=',$nip)->value('golongan');
            // error_log("test golongan : ".$check['golongan']);
            //return dd($check);
            if($check === "PJLP")
            {
                $asigment = DB::table('asigment_pjlp');
            }
            else
            {
                $asigment = DB::table('asigment_asn');
            }
            $asigment = (array) $asigment->where('no_cuti','=',$no_cuti)->first();

            if(Auth::user()->is_kasie)
            {
                $data = ['approval' => $asigment['kasie'],'keterangan'=>$asigment['ket_kasie']];
            }
            elseif(Auth::user()->is_kasubag_tu)
            {
                $data = ['approval' => $asigment['kasubagtu'],'keterangan'=>$asigment['ket_tu']];
            }
            elseif(Auth::user()->is_ppk && $check === 'PJLP')
            {
                $data = ['approval' => $asigment['ppk'],'keterangan'=>$asigment['ket_ppk']];
            }
            else
            {
                return 'approval_fetch_fail';
            }

             return ['approval' => $data['approval'], 'keterangan' => $data['keterangan']];

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
            
            $check = DB::table('data_pegawai')->where('nip','=',$nip)->value('golongan');
            
            if($check === "PJLP")
                {
                    error_log("hit pjlp");
                $assignment = DB::table('asigment_pjlp'); 
                if(Auth::user()->is_kasie)
                {
                    $assignment->where('no_cuti',$no_cuti)->update(['kasie'=>$request->input('status'),'ket_kasie'=>$request->input('keterangan')]);
                }
                elseif(Auth::user()->is_kasubag_tu)
                {
                    $assignment->where('no_cuti',$no_cuti)->update(['kasubagtu'=>$request->input('status'),'ket_tu'=>$request->input('keterangan')]);
                }
                elseif(Auth::user()->is_ppk)
                {
                    $assignment->where('no_cuti',$no_cuti)->update(['ppk'=>$request->input('status'),'ket_ppk'=>$request->input('keterangan')]);
                }
                // TODO : jika semua sudah approve, tembak event

                return 'approval_update_success';
            }
            else {
                error_log('hit asn');
                    $assignment = DB::table('asigment_asn');
                    if(Auth::user()->is_kasie)
                    {
                        $assignment->where('no_cuti',$no_cuti)->update(['kasie'=>$request->input('status'),'ket_kasie'=>$request->input('keterangan')]);
                    }
                    elseif(Auth::user()->is_kasubag_tu)
                    {
                        $assignment->where('no_cuti',$no_cuti)
                        ->update(['kasubagtu'=>$request->input('status'),'ket_tu'=>$request->input('keterangan')]);
                    }
                    // TODO : jika semua sudah approve, tembak event

                    return 'approval_update_success';
            }
            
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

        try{
            $nip = $request->input('nip');
            $no_cuti = $request->input('no_cuti');
    
            $pegawai = (array) DB::table('data_pegawai')->where('nip','=',$nip)->first();
            $jabatan = (array) DB::table('jabatan')->where('no','=',$pegawai['jabatan'])->first();
            if($pegawai['golongan'] === "PJLP")
            {
                $asigment = DB::table('asigment_pjlp');
                $ks = ['ks' => DB::table('asigment_pjlp')->value('kasie')];
                $cuti = DB::table('daftar_cuti_pjlp');
                $g = "PJLP";
                // if($asigment['kasie']==="s"&&$asigment['ppk']===){

                // }
                
            }
            else
            {
                $asigment = DB::table('asigment_asn');
                $ks = ['ks' => DB::table('asigment_asn')->value('kasie')];
                $cuti = DB::table('daftar_cuti_asn');
                $g = "ASN";
            }
            $penempatan = (array) DB::table('penempatan')->where('kode_panggil','=',$pegawai['kode_penempatan'])->first();
            $asigment = (array) $asigment->where('no_cuti','=', $no_cuti)->first();
            $cuti = (array) $cuti->where('nip','=',$nip)->where('id','=',$no_cuti)->first();
            $kasek = ['ksk' => DB::table('data_pegawai')->where('jabatan','=',$pegawai['kasie'])->value('data_pegawai.nama')] ;
            $kasekn = ['kskn' => DB::table('data_pegawai')->where('jabatan','=',$pegawai['kasie'])->value('data_pegawai.nip')] ;
            $jaket = ['jaket' => DB::table('data_pegawai')->where('nip','=',$nip)->value('data_pegawai.keterangan')] ;
            
            error_log('kasie before : '.$asigment['kasie']);
            $asigment['kasie'] = $this->approvalAtasan($asigment['kasie']);
            error_log('kasie now : '.$asigment['kasie']);
            error_log('kasubagtu before : '.$asigment['kasubagtu']);
            $asigment['kasubagtu'] = $this->approvalAtasan($asigment['kasubagtu']);
            error_log('kasubagtu after : '.$asigment['kasubagtu']);

            if($pegawai['golongan'] === "PJLP"){
                $asigment['ppk'] = $this->approvalAtasan($asigment['ppk']);
                $ks['ks'] = $this->approvalAtasan($ks['ks']);}

            $check = array_merge($asigment,$cuti,$ks,$jaket);
            $check = array_merge($check,$pegawai);
            $check = array_merge($check,$jabatan);
            $check = array_merge($check,$penempatan);
            $check = array_merge($check,$kasek,$kasekn);
            
            $start = Carbon::parse($check['tgl_awal'])->locale('id')->isoFormat('DD MMMM YYYY');
            $end = Carbon::parse($check['tgl_akhir'])->locale('id')->isoFormat('DD MMMM YYYY');
            $pd =  Carbon::parse(Carbon::now())->locale('id')->isoFormat('DD MMMM YYYY');
            
            $date = array("start"=>$start, "end" => $end, "print_date" => $pd);
    
            $a =  array_merge($check,$date);
            if($pegawai['golongan']==="PJLP"){
                $pdf = PDF::loadView('doc/print',compact('a'))->setPaper('a4','portrait');
                error_log('nip : '.$nip);
                error_log('PJLP hit');
                error_log('array key : '.implode('|',array_keys($a)));
                error_log('array value : '.implode('|',$a));

                CutiPrintEvent::dispatch($request->input('nip'),$no_cuti);
                //return dd($a);
                
                return $pdf->download();
            }
            else{
                $pdf = PDF::loadView('doc/print1',compact('a'))->setPaper('a4','portrait');
                error_log('nip : '.$nip);
                error_log('ASN hit');
                error_log('array key : '.implode('|',array_keys($a)));
                error_log('array value : '.implode('|',$a));
                CutiPrintEvent::dispatch($nip,$no_cuti);
                //return dd($a);
                return $pdf->download();
            }
            //error_log("test check ".$check); 
            //->set_option('IsRemoteEnabled',TRUE);
    
            //return dd($a);
            
            //return view('print')->with('nip',$nip)->with('no_cuti',$no_cuti)->with($pdf->download('invoice.pdf'));
            // return [
            //     'status' => 'print_success',
            //     'golongan' => $g,
            //     'data' => $pdf->output()
            // ];
            
            //return PDF::loadFile(public_path().'/resource/views/print.blade.php')->save('/path-to/my_stored_file.pdf')->stream('download.pdf');
        
        }
        catch(Throwable $e)
        {
            error_log("Print request fail for ".$nip." no cuti ".$no_cuti." error : ".$e);
            report("Print request fail for ".$nip." no cuti ".$no_cuti." error : ".$e);
            return [
                'status' => 'print_fail_try_caught',
                'data' => null
            ];
        }

    }

    public function testDocument(Request $request)
    {
        $pdf = PDF::loadView('doc/test');
        return null;
    }

    public function approvalAtasan($data)
    {
        switch($data)
        {
            case '-': return 'Belum Dicek'; break;
            case 's': return 'Disetujui'; break;
            case 't': return 'Ditangguhkan'; break;
            case 'u': return 'Diubah';break;
            case 'x': return 'Ditolak';break;
            default : return '-';break;
        }
    }
    public function sisaCuti($pegawaiNIP, $pegawaiNoCuti)
    {
        try
        {
            $nip = $pegawaiNIP;
            $no_cuti = $pegawaiNoCuti;

            $check = (array) DB::table('data_pegawai')->where('nip','=',$nip)->first();

            if($check['golongan'] === 'PJLP')
            {
                $asigment = DB::table('asigment_pjlp');
                $daftar = DB::table('daftar_cuti_pjlp');
                $tahunan = DB::table('cuti_tahunan_pjlp');
            }
            else
            {
                $asigment = DB::table('asigment_asn');
                $daftar = DB::table('daftar_cuti_asn');
                $tahunan = DB::table('cuti_tahunan_asn');
            }


            $status = $asigment->select('selesai')->where('no_cuti','=',$no_cuti)->value('selesai');
            
            if($status == 0)
            {
                $t = $daftar->where('id','=',$no_cuti)->first();
                $totalHari = $t->total_cuti;
                error_log('total hari ; '.$totalHari);
                $item = $tahunan->where('nip','=',$nip)->first();
    
                $sisa = $item->sisa;
                error_log('sisa : '.$sisa);
                $sisa = $sisa - $totalHari;
    
                $terpakai = $item->terpakai;
                $terpakai = $terpakai + $totalHari;
    
                $i = $tahunan->where('nip','=',$nip);

                $i->update(array(
                        'sisa' => $sisa,
                        'terpakai' => $terpakai
                    ));
    
                if($check !== 'PJLP')
                {

                    /**
                     * NOTE : 
                     * Jika user tidak cuti / sisa cuti <= 6, maka tahun depan N1 dan N2 = 6
                     * Jika user cuti melebihi 6 hari, maka tahun depan N1 dan N2 = 0
                     * 
                     * Code dibawah mengasumsikan penyesuaian nilai N1 dan N2 = 6, alias tidak cuti 2 tahun.
                     * N2 diprioritaskan utk dikurang terlebih dahulu
                     */

                    $n1 = $item->n1;
                    $n2 = $item->n2;


                    if($n2 == 6 && $n1==6 && $sisa == 12)
                    {
                        $n2=$n2-$totalHari;
                    }
                    else if($n2 == 0 && $n2==6 && $sisa==12){
                        $n1=$n1-$totalHari;
                    }
                    else if($n2==0 && $n1==0 && $sisa==12){
                        $sisa=$sisa-$totalHari;
                    }
                    $i->update(array(
                        'sisa' => $sisa,
                        'n1' => $n1,
                        'n2' => $n2
                    ));
                }
                $asigment->where('no_cuti','=',$no_cuti)->update(array('selesai' => 1));
            }
            else
                error_log('kelewat');
        }
        catch(Throwable $e)
        {
            error_log('error handle cuti print : '.$e);
        }
    }
}
