<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;

class PegawaiController extends Controller
{
    function addPegawai($data)
    {
        if($data['mas_ker'] === 'today')
            $data['mas_ker'] = Carbon::now()->toDateString();

        $golongan = $data['golongan'];

        if($golongan === 'PJLP')
            $data['nrk'] = '';

        $dibuat = Carbon::now()->toDateTimeString();

        DB::table('data_pegawai')->where('nip','=',$data['nip'])->insert([
            'nip' => $data['nip'],
            'nrk' => $data['nrk'],
            'nama' => $data['nama'],
            'golongan' => $data['golongan'],
            'jabatan' => $data['jabatan'],
            'kasie' => $data['kasie'],
            'atasan' =>$data['atasan'],
            'pendidikan' => $data['pendidikan'],
            'kode_penempatan' => $data['kode_penempatan'],
            'kompi' => $data['kompi'],
            'keterangan' => $data['keterangan'],
            'jabket' => $data['jabket'],
            'mas_ker' => $data['mas_ker'],
            'created_at' => $dibuat,
            'updated_at' => $dibuat
        ]);


        if($golongan === 'PJLP')
        {
            if(!DB::table('cuti_tahunan_pjlp')->where('nip','=',$data['nip'])->exists())
                DB::table('cuti_tahunan_pjlp')->insert([
                    'nip' => $data['nip'],
                    'kuota' => 12,
                    'sisa' => 12,
                    'terpakai' => 0,
                    'tahun' => date('Y')
                ]);
        }
        else
        {
            if(!DB::table('cuti_tahunan_asn')->where('nip','=',$data['nip'])->exists())
                DB::table('cuti_tahunan_asn')->insert([
                    'nip' => $data['nip'],
                    'kuota' => 12,
                    'sisa' => 12,
                    'terpakai' => 0,
                    'n1' => 0,
                    'n2' => 0,
                    'tahun' => date('Y')
                ]);
        }
    }

    function updatePegawai($data)
    {
        error_log('got into update');
        if($data['mas_ker'] === 'today')
            $data['mas_ker'] = Carbon::now()->toDateString();

        error_log('nip : '.$data['oldNip']);


            error_log('nip contains tags, nip : '.$data['oldNip']);
            $data['oldNip'] = html_entity_decode($data['oldNip'],ENT_QUOTES);
            error_log('nip : '.$data['oldNip']);


        $old_golongan = DB::table('data_pegawai')->where('nip','=',$data['oldNip'])->value('golongan');

        if($data['golongan'] !== $old_golongan)
        {
            if($old_golongan === "PJLP")
            {
                if(DB::table('cuti_tahunan_pjlp')->where('nip','=',$data['nip'])->exists())
                    DB::table('cuti_tahunan_pjlp')->where('nip','=',$data['nip'])->delete();

                if(!DB::table('cuti_tahunan_asn')->where('nip','=',$data['nip'])->exists())
                    DB::table('cuti_tahunan_asn')->insert([
                        'nip' => $data['nip'],
                        'kuota' => 12,
                        'sisa' => 12,
                        'terpakai' => 0,
                        'n1' => 0,
                        'n2' => 0,
                        'tahun' => date('Y')
                    ]);
            }
        }

        $diubah = Carbon::now()->toDateTimeString();

        DB::table('data_pegawai')->where('nip','=',$data['oldNip'])->update([
            'nip' => $data['nip'],
            'nrk' => $data['nrk'],
            'nama' => $data['nama'],
            'golongan' => $data['golongan'],
            'jabatan' => $data['jabatan'],
            'kasie' => $data['kasie'],
            'atasan' =>$data['atasan'],
            'pendidikan' => $data['pendidikan'],
            'kode_penempatan' => $data['kode_penempatan'],
            'kompi' => $data['kompi'],
            'keterangan' => $data['keterangan'],
            'jabket' => $data['jabket'],
            'mas_ker' => $data['mas_ker'],
            'updated_at' => $diubah
        ]);
        error_log('pass modified');
    }

    function deletePegawai($nip)
    {
        $type = DB::table('data_pegawai')->where('nip','=',$nip)->value('golongan');

        if($type === 'PJLP' || $type === 'pjlp')
        {
            if(DB::table('cuti_tahunan_pjlp')->where('nip','=',$nip)->exists())
                DB::table('cuti_tahunan_pjlp')->where('nip','=',$nip)->delete();
        }
        else
        {
            if(DB::table('cuti_tahunan_asn')->where('nip','=',$nip)->exists())
                DB::table('cuti_tahunan_asn')->where('nip','=',$nip)->delete();
        }

        DB::table('data_pegawai')->where('nip','=',$nip)->delete();
    }

    public function checkNip(Request $r)
    {
        try{

            $nip = $r->input('assignedNip');
            $verify = DB::table('data_pegawai')->where('nip','=',$nip)->count();
            $val = 0;
            if($verify != 0)
                $val = 1;
            return $val;
        }
        catch(Throwable $e)
        {
            error_log('Failure on attempting to verify nip '.$nip.' error : '.$e);
            report('Failure on attempting to verify nip '.$nip.' error : '.$e);
            return false;
        }
    }

    public function checkNrk(Request $r)
    {
        try{

            $nip = $r->input('assignedNip');
            $verify = DB::table('data_pegawai')->where('nrk','=',$nip)->count();
            $val = 0;
            if($verify != 0)
                $val = 1;
            return $val;
        }
        catch(Throwable $e)
        {
            error_log('Failure on attempting to verify nrk '.$nip.' error : '.$e);
            report('Failure on attempting to verify nrk '.$nip.' error : '.$e);
            return false;
        }
    }

    public function fetchByNip(Request $r)
    {
        try{

            $nip = $r->input('assignedNip');
            $q = DB::table('data_pegawai as dp')->join('jabatan as j','dp.jabatan','=','j.no')
                ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                ->where('dp.nip','=',$nip)
                ->get([
                    'dp.nama',
                    'dp.golongan',
                    'j.jabatan',
                    'p.penempatan',
                    'dp.nip',
                    'dp.jabket'
                ]);


            return $q;

        }
        catch(Throwable $e)
        {
            error_log('Failure on attempting to fetch by nip '.$nip.' error : '.$e);
            report('Failure on attempting to fetch by nip '.$nip.' error : '.$e);
            return ['-','-','-','-','-','-'];
        }
    }

    public function fetchByNrk(Request $r)
    {
        try{

            $nip = $r->input('assignedNip');
            $q = DB::table('data_pegawai as dp')->join('jabatan as j','dp.jabatan','=','j.no')
                ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                ->where('dp.nrk','=',$nip)
                ->get([
                    'dp.nama',
                    'dp.golongan',
                    'j.jabatan',
                    'p.penempatan',
                    'dp.nip',
                    'dp.jabket'
                ]);


            return $q;

        }
        catch(Throwable $e)
        {
            error_log('Failure on attempting to fetch by nrk '.$nip.' error : '.$e);
            report('Failure on attempting to fetch by nrk '.$nip.' error : '.$e);
            return ['-','-','-','-','-','-'];
        }
    }

    public function addSinglePegawai(Request $request)
    {
        try{


            $nip = $request->input('nip');


            if(DB::table('data_pegawai')->where('nip','=',$nip)->exists())
                return "fail_add_pegawai_exist";

                $nrk = $request->input('nrk');
                $nama = $request->input('nama');
                $jabatan = $request->input('jabatan');
                $golongan = $request->input('golongan');
                $penempatan = $request->input('kode_penempatan');
                $kompi = $request->input('kompi');
                $pendidikan = $request->input('pendidikan');
                $mas_ker = $request->input('mas_ker');
                $atasan = $request->input('atasan');
                $keterangan = $request->input('keterangan');
                $jabket = $request->input('jabket');

                if($request->input('masKerToday'))
                    $mas_ker = 'today';
                
                $k = new JabatanController();

                $kasie = $k->getKasieFromPenempatan($penempatan);
            
            $data = [
                'nip' => $nip,
                'nrk' => $nrk,
                'nama' => $nama,
                'golongan' => $golongan,
                'jabatan' => $jabatan,
                'atasan' =>$atasan,
                'pendidikan' => $pendidikan,
                'kode_penempatan' => $penempatan,
                'kompi' => $kompi,
                'keterangan' => $keterangan,
                'jabket' => $jabket,
                'mas_ker' => $mas_ker,
                'kasie' => $kasie
            ];

            $this->addPegawai($data);
            return "success_add_pegawai";


        }
        catch(Throwable $e)
        {
            error_log("Add pegawai error : "+$e);
            report("Add pegawai error : "+$e);
            return "fail_add_pegawai_try_caught";
        }
    }

    public function updateSinglePegawai(Request $r)
    {
        try{

            $mas_ker = $r->input('mas_ker');

            if($r->input('masKerToday'))
            $mas_ker = 'today';

            $k = new JabatanController();

            $data = [
                'nip' => $r->input('nip'),
                'oldNip' => $r->input('oldNip'),
                'nrk' => $r->input('nrk'),
                'nama' => $r->input('nama'),
                'golongan' => $r->input('golongan'),
                'jabatan' => $r->input('jabatan'),
                'kasie' => $k->getKasieFromPenempatan($r->input('kode_penempatan')),
                'atasan' => $r->input('atasan'),
                'pendidikan' => $r->input('pendidikan'),
                'kode_penempatan' => $r->input('kode_penempatan'),
                'kompi' => $r->input('kompi'),
                'keterangan' => $r->input('keterangan'),
                'jabket' => $r->input('jabket'),
                'mas_ker' => $mas_ker,

            ];
            
            $this->updatePegawai($data);
            return 'success_update_pegawai';

        }
        catch(Throwable $e)
        {
            error_log('Failed to update pegawai with nip '.$r->input('nip').' error : '.$e);
            report('Failed to update pegawai with nip '.$r->input('nip').' error : '.$e);
            return "fail_update_pegawai_try_caught";
        }
    }

    public function deleteSinglePegawai(Request $r)
    {
        try{

            $nip = $r->input('nip');

            if(!DB::table('data_pegawai')->where('nip','=',$nip)->exists())
                return "fail_delete_pegawai_exist";
            
            $this->deletePegawai($nip);
            return 'success_delete_pegawai';

        }
        catch(Throwable $e)
        {
            error_log('Failed to delete pegawai with nip '.$nip.' error : '.$e);
            report('Failed to delete pegawai with nip '.$nip.' error : '.$e);
            return "fail_delete_pegawai_try_caught";
        }
    }

    public function exportDataPegawaiToXlsx(Request $r)
    {
        try{

        }
        catch(Throwable $e)
        {
            error_log("Failed to Export Data Pegawai to Xlsx, error : ".$e);
            report("Failed to Export Data Pegawai to Xlsx, error : ".$e);
            return 'fail_export_try_caught';
        }
    }
}

?>