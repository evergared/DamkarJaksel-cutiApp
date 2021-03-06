<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JabatanController;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Throwable;

class TabelController extends Controller
{

    function tampilKompi($data)
    {
        switch($data)
        {
            case 'A': return 'Ambon'; break;
            case 'B': return 'Bandung';break;
            case 'C': return 'Cepu';break;
            default : return '-';break;
        }
    }
    
    public function approvalAtasan($data)
    {
        switch($data)
        {
            case '-': return '<strong id="bc">Belum Dicek</strong>'; break;
            case 's': return '<strong id="s">Disetujui</strong>'; break;
            case 't': return '<strong id="t">Ditangguhkan</strong>'; break;
            case 'u': return '<strong id="u">Perubahan</strong>';break;
            case 'x': return '<strong id="x">Ditolak</strong>';break;
            default : return '<strong id="er">Kesalahan Data</strong>';break;
        }
    }

    public function createTableMaster(Request $request)
    {
        $d = DB::table('data_pegawai as dp')->join('jabatan as j','dp.jabatan','=','j.no')
            ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
            ->get([
                'dp.nip',
                'dp.nrk',
                'dp.nama',
                'dp.golongan',
                'dp.pendidikan',
                'dp.kode_penempatan',
                'p.penempatan',
                'dp.kompi',
                'dp.jabatan as kode_jabatan',
                'dp.kasie',
                'dp.keterangan',
                'dp.jabket',
                'j.jabatan',
                'dp.mas_ker'
            ]);
        
            return DataTables::of($d)
                ->addIndexColumn()
                ->addColumn('grup', function($data){
                    $dat = (array)$data;
                    return $this->tampilKompi($dat['kompi']);
                })
                ->addColumn('tindakan', function(){
                    return '<b>Tindakan</b>';
                })
                ->rawColumns(['tindakan'])
                ->make(true);
    }

    public function createTableASN(Request $request)
    {
        $d = DB::table('data_pegawai as dp')->join('jabatan as j','dp.jabatan','=','j.no')
            ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
            ->where('golongan','!=','PJLP')->get([
                'dp.nip',
                'dp.nrk',
                'dp.nama',
                'dp.golongan',
                'dp.pendidikan',
                'p.penempatan',
                'dp.kompi',
                'j.jabatan',
                'dp.mas_ker'
            ]);
        
            return DataTables::of($d)
                ->addIndexColumn()
                ->addColumn('grup', function($data){
                    $dat = (array)$data;
                    return $this->tampilKompi($dat['kompi']);
                })
                ->addColumn('tindakan', function(){
                    return '<b>Tindakan</b>';
                })
                ->rawColumns(['tindakan'])
                ->make(true);
    }

    public function createTablePJLP(Request $request)
    {
        $d = DB::table('data_pegawai as dp')->join('jabatan as j','dp.jabatan','=','j.no')
            ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
            ->where('golongan','=','PJLP')->get([
                'dp.nip',
                'dp.nama',
                'dp.golongan',
                'dp.pendidikan',
                'p.penempatan',
                'dp.kompi',
                'j.jabatan',
                'dp.mas_ker'
            ]);
        
            return DataTables::of($d)
                ->addIndexColumn()
                ->addColumn('grup', function($data){
                    $dat = (array)$data;
                    return $this->tampilKompi($dat['kompi']);
                })
                ->addColumn('tindakan', function(){
                    return '<b>Tindakan</b>';
                })
                ->rawColumns(['tindakan'])
                ->make(true);
    }

    public function createTableJabatan(Request $request)
    {
        $query = DB::table('jabatan')->get();

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('tindakan', function(){
            return '<b>Tindakan</b>';
        })
        ->rawColumns(['tindakan'])
        ->make(true);
    }

    public function createTablePenempatan(Request $request)
    {
        $query = DB::table('penempatan')->get();

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('tindakan', function(){
            return '<b>Tindakan</b>';
        })
        ->rawColumns(['tindakan'])
        ->make(true);
    }

    public function createTablePLT(Request $request)
    {
        $query = DB::table('plt as pt')->join('data_pegawai as dp','pt.nip_pelaksana','=','dp.nip')
                ->leftJoin('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                ->join('jabatan as j','pt.kode_jabatan','=','j.no')
                ->get([
                    'pt.nip_pelaksana as nip',
                    'pt.kode_jabatan',
                    'pt.keterangan',
                    'j.jabatan',
                    'dp.nama',
                    'p.penempatan'
                ]);

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('tindakan', function(){
            return '<b>Tindakan</b>';
        })
        ->rawColumns(['tindakan'])
        ->make(true);
    }

    public function createTableAssignmentASN(Request $request)
    {
        $query = DB::table("asigment_asn as a")->join('daftar_cuti_asn as d','a.no_cuti','=','d.id')
        ->join('cuti_tahunan_asn as ct','d.nip','=','ct.nip');

        // TODO : buat tampil tabel assignment asn untuk karu
        // TODO : buat tampil tabel assignment asn untuk katon
        try{

                if(Auth::user()->is_kasie)
                {
                    $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->where('dp.kasie','=',Auth::user()->jabatan)
                        ->get([
                            'dp.nip',
                            'dp.nrk',
                            'dp.nama',
                            'a.no_cuti',
                            'a.kasie',
                            'a.ket_kasie',
                            'd.jenis_cuti',
                            'd.alasan',
                            'd.tlpn',
                            'd.alamat',
                            'd.tgl_awal',
                            'd.tgl_akhir',
                            'd.total_cuti',
                            'd.tgl_pengajuan'
                        ]);

                        $dt =  DataTables::of($query)
                                ->addIndexColumn()
                                ->addColumn('p_kasie',function($data){
                                    $dat = (array) $data;
                                    return $this->approvalAtasan($dat['kasie']);
                                })
                                ->addColumn('tindakan',function($row){
                                        $btn = '<a href="" class="act_ btn btn-primary btn-sm" data-galileo = "'.$row->nip.'" data-figaro="'.$row->no_cuti.'">Ubah Persetujuan</a>';
                                        return $btn;
                                })
                                ->addColumn('k_kasie',function($data)
                                {
                                    $dat = (array) $data;
                                    return "<i id='ket'>".$dat['ket_kasie']."</i>";
                                })
                                ->rawColumns(['p_kasie','tindakan','k_kasie'])
                                ->make(true);
                }
                elseif(Auth::user()->is_kasubag_tu)
                {
                    $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->where('a.kasie','=','s')
                        ->get([
                            'dp.nip',
                            'dp.nrk',
                            'dp.nama',
                            'a.no_cuti',
                            'a.kasubagtu',
                            'a.ket_tu',
                            'd.jenis_cuti',
                            'd.alasan',
                            'd.tlpn',
                            'd.alamat',
                            'd.tgl_awal',
                            'd.tgl_akhir',
                            'd.total_cuti',
                            'd.tgl_pengajuan'
                        ]);

                        $dt =  DataTables::of($query)
                                ->addIndexColumn()
                                ->addColumn('p_tu',function($data){
                                    $dat = (array) $data;
                                    return $this->approvalAtasan($dat['kasubagtu']);
                                })
                                ->addColumn('tindakan',function($row){
                                        $btn = '<a href="" class="act_ btn btn-primary btn-sm" data-galileo = "'.$row->nip.'" data-figaro="'.$row->no_cuti.'">Ubah Persetujuan</a>';
                                        return $btn;
                                })
                                ->addColumn('k_tu',function($data)
                                {
                                    $dat = (array) $data;
                                    return "<i id='ket'>".$dat['ket_tu']."</i>";
                                })
                                ->rawColumns(['p_tu','tindakan','k_tu'])
                                ->make(true);
                }
                elseif(Auth::user()->is_kasudin)
                {
                    $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                        ->where('a.kasie','=','s')
                        ->where('a.kasubagtu','=','s')
                        ->get([
                            'dp.nip',
                            'dp.nrk',
                            'dp.nama',
                            'p.penempatan',
                            'a.no_cuti',
                            'a.kasie',
                            'a.ket_kasie',
                            'a.kasubagtu',
                            'a.ket_tu',
                            'd.jenis_cuti',
                            'd.alasan',
                            'd.tlpn',
                            'd.alamat',
                            'd.tgl_awal',
                            'd.tgl_akhir',
                            'd.total_cuti',
                            'd.tgl_pengajuan'
                        ]);

                        $dt =  DataTables::of($query)
                                ->addIndexColumn()
                                ->addColumn('p_kasie',function($data){
                                    $dat = (array) $data;
                                    return $this->approvalAtasan($dat['kasie']);
                                })
                                ->addColumn('k_kasie',function($data)
                                {
                                    $dat = (array) $data;
                                    return "<i id='ket'>".$dat['ket_kasie']."</i>";
                                })
                                ->addColumn('p_tu',function($data){
                                    $dat = (array) $data;
                                    return $this->approvalAtasan($dat['kasubagtu']);
                                })
                                ->addColumn('k_tu',function($data)
                                {
                                    $dat = (array) $data;
                                    return "<i id='ket'>".$dat['ket_tu']."</i>";
                                })
                                ->rawColumns(['p_tu','k_tu','p_kasie','k_kasie'])
                                ->make(true);
                }
                elseif(Auth::user()->is_admin)
                {
                    $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                        ->get([
                            'dp.nip',
                            'dp.nama',
                            'p.penempatan',
                            'a.no_cuti',
                            'd.jenis_cuti',
                            'd.alasan',
                            'd.tlpn',
                            'd.alamat',
                            'd.tgl_awal',
                            'd.tgl_akhir',
                            'd.total_cuti',
                            'ct.sisa',
                            'd.tgl_pengajuan',
                            'd.alamat'
                        ]);

                        $dt =  DataTables::of($query)
                                ->addIndexColumn()
                                ->addColumn('tindakan',function($row){
                                        $deleteRoute = route('report.asn.delete',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                                        $appRoute = route('report.asn.app',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                                        
                                        $btn = '<a href="'.$appRoute.'" class="edit btn btn-info btn-sm">Ambil Surat Cuti</a>';
                                        $btn = $btn.'<a href="'.$deleteRoute.'" class="edit btn btn-danger btn-sm">Hapus</a>';
                                        $btn = $btn.'<button class="btn" data-toggle="modal" data-target="#form-cuti-'.$row->no_cuti.'"
                                                data-nip="'.$row->nip.'">test</button>';
                                    return $btn;
                                })
                                ->rawColumns(['tindakan'])
                                ->make(true);
                }

                return $dt;

        }
        catch(Throwable $e)
        {
            report('Failed to load asigment table ASN on '.$e);
            error_log('Failed to load asigment table ASN on '.$e);
        }
        

        // if($request->ajax())
        // {
        //     return $dt; 
        // }
        //return view('dashboard/report');
    }

    public function createTableAssignmentPJLP(Request $request)
    {
        $query = DB::table("asigment_pjlp as a")->join('daftar_cuti_pjlp as d','a.no_cuti','=','d.id')
            ->join('cuti_tahunan_pjlp as ct','d.nip','=','ct.nip');
        // TODO : buat tampil tabel assignment pjlp untuk karu
        // TODO : buat tampil tabel assignment pjlp untuk katon

        try{
            if(Auth::user()->is_kasie)
            {
                $query_kasie = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                    ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                    ->where('dp.kasie','=',Auth::user()->jabatan)
                    ->get([
                        'dp.nip',
                        'dp.nrk',
                        'dp.nama',
                        'p.penempatan',
                        'a.no_cuti',
                        'a.kasie',
                        'a.ket_kasie',
                        'a.ppk as p',
                        'a.ket_ppk as k',
                        'd.jenis_cuti',
                        'd.alasan',
                        'd.tlpn',
                        'd.alamat',
                        'd.tgl_awal',
                        'd.tgl_akhir',
                        'd.total_cuti',
                        'd.tgl_pengajuan'
                    ]);

                $query_n = $query_kasie;

                    if(Auth::user()->is_ppk)
                    {
                        $query_ppk = DB::table("asigment_pjlp as a")->join('daftar_cuti_pjlp as d','a.no_cuti','=','d.id')
                        ->join('cuti_tahunan_pjlp as ct','d.nip','=','ct.nip')->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                        ->where('a.kasie','=','s')
                        ->get([
                        'dp.nip',
                        'dp.nrk',
                        'dp.nama',
                        'p.penempatan',
                        'a.no_cuti',
                        'a.kasie',
                        'a.ket_kasie',
                        'a.ppk as p',
                        'a.ket_ppk as k',
                        'd.jenis_cuti',
                        'd.alasan',
                        'd.tlpn',
                        'd.alamat',
                        'd.tgl_awal',
                        'd.tgl_akhir',
                        'd.total_cuti',
                        'd.tgl_pengajuan'
                    ]);
                        
                        $query_n = $query_ppk->union($query_kasie);
                    }

                    
                    $dt =  DataTables::of($query_n)
                            ->addIndexColumn()
                            ->addColumn('p_kasie',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['kasie']);
                            })
                            ->addColumn('p',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['p']);
                            })
                            ->addColumn('tindakan',function($row){
                                    $btn = '<a href="" class="act_ btn btn-primary btn-sm" data-galileo = "'.$row->nip.'" data-figaro="'.$row->no_cuti.'">Ubah Persetujuan</a>';
                                    return $btn;
                            })
                            ->addColumn('k_kasie',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_kasie']."</i>";
                            })
                            ->addColumn('k',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['k']."</i>";
                            })
                            ->rawColumns(['p_kasie','tindakan','k_kasie','p','k'])
                            ->make(true);
            }
            elseif(Auth::user()->is_kasubag_tu)
            {
                $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                    ->where('a.kasie','=','s')->where('a.ppk','=','s')
                    ->get([
                        'dp.nip',
                        'dp.nrk',
                        'dp.nama',
                        'a.no_cuti',
                        'a.kasubagtu',
                        'a.ket_tu',
                        'd.jenis_cuti',
                        'd.alasan',
                        'd.alamat',
                        'd.tlpn',
                        'd.tgl_awal',
                        'd.tgl_akhir',
                        'd.total_cuti',
                        'd.tgl_pengajuan'
                    ]);

                    $dt =  DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('p_tu',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['kasubagtu']);
                            })
                            ->addColumn('tindakan',function($row){
                                    $btn = '<a href="" class="act_ btn btn-primary btn-sm" data-galileo = "'.$row->nip.'" data-figaro="'.$row->no_cuti.'">Ubah Persetujuan</a>';
                                    return $btn;
                            })
                            ->addColumn('k_tu',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_tu']."</i>";
                            })
                            ->rawColumns(['p_tu','tindakan','k_tu'])
                            ->make(true);
            }
            elseif(Auth::user()->ppk)
            {
                $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                    ->where('a.kasie','=','s')
                    ->get([
                        'dp.nip',
                        'dp.nrk',
                        'dp.nama',
                        'a.no_cuti',
                        'a.ppk',
                        'a.ket_ppk',
                        'd.jenis_cuti',
                        'd.alasan',
                        'd.tlpn',
                        'd.alamat',
                        'd.tgl_awal',
                        'd.tgl_akhir',
                        'd.total_cuti',
                        'd.tgl_pengajuan'
                    ]);

                    $dt =  DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('p_ppk',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['ppk']);
                            })
                            ->addColumn('tindakan',function($row){
                                    $btn = '<a href="" class="act_ btn btn-primary btn-sm" data-galileo = "'.$row->nip.'" data-figaro="'.$row->no_cuti.'">Ubah Persetujuan</a>';
                                    return $btn;
                            })
                            ->addColumn('k_ppk',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_ppk']."</i>";
                            })
                            ->rawColumns(['p_ppk','tindakan','k_ppk'])
                            ->make(true);
            }
            elseif(Auth::user()->is_kasudin)
            {
                $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                    ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                    ->where('a.kasie','=','s')
                    ->where('a.kasubagtu','=','s')
                    ->where('a.ppk','=','s')
                    ->get([
                        'dp.nip',
                        'dp.nrk',
                        'dp.nama',
                        'p.penempatan',
                        'a.no_cuti',
                        'a.kasie',
                        'a.ket_kasie',
                        'a.kasubagtu',
                        'a.ket_tu',
                        'a.ppk',
                        'a.ket_ppk',
                        'd.jenis_cuti',
                        'd.alasan',
                        'd.tlpn',
                        'd.alamat',
                        'd.tgl_awal',
                        'd.tgl_akhir',
                        'd.total_cuti',
                        'd.tgl_pengajuan'
                    ]);

                    $dt =  DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('p_kasie',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['kasie']);
                            })
                            ->addColumn('k_kasie',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_kasie']."</i>";
                            })
                            ->addColumn('p_tu',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['kasubagtu']);
                            })
                            ->addColumn('k_tu',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_tu']."</i>";
                            })
                            ->addColumn('p_ppk',function($data){
                                $dat = (array) $data;
                                return $this->approvalAtasan($dat['ppk']);
                            })
                            ->addColumn('k_ppk',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_ppk']."</i>";
                            })
                            ->rawColumns(['p_tu','k_tu','p_kasie','k_kasie','p_ppk','k_ppk'])
                            ->make(true);
            }
            elseif(Auth::user()->is_admin)
            {
                $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                    ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                    ->get([
                        'dp.nip',
                        'dp.nama',
                        'p.penempatan',
                        'a.no_cuti',
                        'd.jenis_cuti',
                        'd.tgl_awal',
                        'd.alasan',
                        'd.tlpn',
                        'd.alamat',
                        'ct.sisa',
                        'd.tgl_akhir',
                        'd.total_cuti',
                        'd.tgl_pengajuan'
                    ]);


                $dt = DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('tindakan',function($row){
                        error_log('creating buttons');
                        if(Auth::user()->is_admin)
                        {
                            $deleteRoute = route('report.pjlp.delete',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                            $appRoute = route('report.pjlp.app',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                            
                            $btn = '<a href="'.$appRoute.'" class="edit btn btn-info btn-sm">Ambil Surat Cuti</a>';
                            $btn = $btn.'<a href="'.$deleteRoute.'" class="edit btn btn-danger btn-sm">Hapus</a>'; 
                        }
                            
                            

                        return $btn;
                    })
                    ->rawColumns(['tindakan'])
                    ->make(true);

                    
            }

            // if($request->ajax())
            // {
            //     return $dt;
            // }
            
            // return view('dashboard/report');
            return $dt;
        }
        catch(Throwable $e)
        {
            report('Failed to load asigment table PJLP on '.$e);
            error_log('Failed to load asigment table PJLP on '.$e);
        }

        
    }

    public function createTableAssignmentSELF(Request $request)
    {
        if(in_array('ASN',Auth::user()->roles))
        {
            $tabel = DB::table('daftar_cuti_asn','d')->join('asigment_asn as a','d.id','=','a.no_cuti');
            $col = [
                'd.nip',
                'd.alasan',
                'd.tlpn',
                'd.alamat',
                'a.no_cuti',
                'a.kasie',
                'a.ket_kasie',
                'a.kasubagtu',
                'a.ket_tu',
                'd.jenis_cuti',
                'd.tgl_awal',
                'd.tgl_akhir',
                'd.total_cuti',
                'd.tgl_pengajuan'
            ];

        }
        elseif(in_array('PJLP',Auth::user()->roles))
        {
            $tabel = DB::table('daftar_cuti_pjlp','d')->join('asigment_pjlp as a','d.id','=','a.no_cuti');
            $col = [
                'd.nip',
                'd.alasan',
                'd.tlpn',
                'd.alamat',
                'a.no_cuti',
                'a.kasie',
                'a.ket_kasie',
                'a.kasubagtu',
                'a.ket_tu',
                'a.ppk',
                'a.ket_ppk',
                'd.jenis_cuti',
                'd.tgl_awal',
                'd.tgl_akhir',
                'd.total_cuti',
                'd.tgl_pengajuan'
            ];

        }

        $query = $tabel->where('d.nip','=',Auth::user()->nip)
            ->get($col);
        
        if($request->ajax())
        {
            if(in_array('PJLP',Auth::user()->roles))
            {
                return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tindakan',function($row){

                    $deleteRoute = route('report.self.delete',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                    $appRoute = route('report.self.app',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                    
                    $btn = '<a href="'.$appRoute.'" class="edit btn btn-info btn-sm">Ambil Surat Cuti</a>';
                    $btn = $btn.'<a href="'.$deleteRoute.'" class="edit btn btn-danger btn-sm">Hapus</a>';
                    return $btn;
                })
                ->addColumn('p_kasie',function($data){
                    $dat = (array) $data;
                    return $this->approvalAtasan($dat['kasie']);
                })
                ->addColumn('k_kasie',function($data)
                        {
                            $dat = (array) $data;
                            return "<i id='ket'>".$dat['ket_kasie']."</i>";
                        })
                ->addColumn('p_tu',function($data){
                    $dat = (array) $data;
                    return $this->approvalAtasan($dat['kasubagtu']);
                })
                ->addColumn('k_tu',function($data)
                        {
                            $dat = (array) $data;
                            return "<i id='ket'>".$dat['ket_tu']."</i>";
                        })
                ->addColumn('p_ppk',function($data){
                    $dat = (array) $data;
                    return $this->approvalAtasan($dat['ppk']);
                })
                ->addColumn('k_ppk',function($data)
                        {
                            $dat = (array) $data;
                            return "<i id='ket'>".$dat['ket_ppk']."</i>";
                        })
                ->rawColumns(['tindakan','p_kasie','k_kasie','p_tu','k_tu','p_ppk','k_ppk'])
                ->make(true);
            }
            elseif(in_array('ASN',Auth::user()->roles))
            {
                return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tindakan',function($row){

                    $deleteRoute = route('report.self.delete',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                    $appRoute = route('report.self.app',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                    
                    $btn = '<a href="'.$appRoute.'" class="edit btn btn-primary btn-sm">Ambil Surat Cuti</a>';
                    $btn = $btn.'<a href="'.'" class="edit btn btn-info btn-sm">Ubah</a>';
                    $btn = $btn.'<a href="'.$deleteRoute.'" class="edit btn btn-danger btn-sm">Hapus</a>';
                    return $btn;
                })
                ->addColumn('p_kasie',function($data){
                    $dat = (array) $data;
                    return $this->approvalAtasan($dat['kasie']);
                })
                ->addColumn('k_kasie',function($data)
                        {
                            $dat = (array) $data;
                            return "<i id='ket'>".$dat['ket_kasie']."</i>";
                        })
                ->addColumn('p_tu',function($data){
                    $dat = (array) $data;
                    return $this->approvalAtasan($dat['kasubagtu']);
                })
                ->addColumn('k_tu',function($data)
                        {
                            $dat = (array) $data;
                            return "<i id='ket'>".$dat['ket_tu']."</i>";
                        })
                ->rawColumns(['tindakan','p_kasie','k_kasie','p_tu','k_tu'])
                ->make(true);
            }

            
        }
        
        return view('dashboard/report');
    }

    public function createTablePLTAssignmentASN(Request $request)
    {
        $jabatanController = new JabatanController();
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
        {
            error_log("Fetch PLT Assignment ASN data failure with message : ".$list_jabatan);
            report("Fetch PLT Assignment ASN data failure with message : ".$list_jabatan);
            return null;
        }

        try{

            $base_query = DB::table("asigment_asn as a")->join('daftar_cuti_asn as d','a.no_cuti','=','d.id')
                        ->join('cuti_tahunan_asn as ct','d.nip','=','ct.nip')
                        ->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil');
 
            $base_query_raw = "asigment_asn as a inner join daftar_cuti_asn as d on a.no_cuti = d.id inner join cuti_tahunan_asn as ct on d.nip = ct. nip inner join data_pegawai as dp on d.nip = dp.nip inner join penempatan as p on dp.kode_penempatan = p.kode_panggil";
            
            
            $get_columns = [    
                'dp.nip',
                'dp.nrk',
                'dp.nama',
                'p.penempatan',
                'a.no_cuti',
                'a.kasie',
                'a.ket_kasie',
                'a.kasubagtu',
                'a.ket_tu',
                'd.jenis_cuti',
                'd.alasan',
                'd.tlpn',
                'd.alamat',
                'd.tgl_awal',
                'd.tgl_akhir',
                'd.total_cuti',
                'd.tgl_pengajuan'
            ];

            $f_kasie = false;
            $f_tu = false;

            // fetch data kasie
            if($jabatanController->is_user_plt_kasie())
            {
                $list_j_kasie = $jabatanController->getJabatanPltKasie();
                $query_kasie = $base_query;

                if(count($list_j_kasie) == 1)
                {
                    $query_kasie = $query_kasie->where('dp.kasie','=',array_pop($list_j_kasie))->get($get_columns);
                }
                else
                {
                    // multi
                    $query = "select ".implode(',',$get_columns)." from ".$base_query_raw;

                    $w = ' dp.kasie = '.array_pop($list_j_kasie);

                    foreach($list_j_kasie as $jk){
                        error_log('jk is '.$jk);
                        $w = $w.' or dp.kasie = '.$jk;
                    }
                    $query = $query .' where '. $w;

                    $query_kasie = DB::select($query);
                    // error_log($query_kasie);
                }


                if(count($query_kasie) > 0)
                    $f_kasie = true;
            }

            // fetch data tu
            if($jabatanController->is_user_plt_tu())
            {
                $query_tu = $base_query;

                $query_tu = $query_tu->where('a.kasie','=','s')->get($get_columns);

                if($query_tu->count() != 0)
                    $f_tu = true;
            }

            // create final query
            if($f_kasie && $f_tu)
                $final_query = $query_kasie->merge($query_tu);
            else if($f_kasie)
                $final_query = $query_kasie;
            else if($f_tu)
                $final_query = $query_tu;
            else
                $final_query = $base_query->where('dp.nip','=')->get($get_columns); // dummy query

            // create table for datatables
            return DataTables::of($final_query)
                    ->addIndexColumn()
                    ->addColumn('tindakan',function($row){
                        return '<p>';
                    })
                    ->addColumn('p_kasie',function($data){
                        $dat = (array) $data;
                        return $this->approvalAtasan($dat['kasie']);
                    })
                    ->addColumn('k_kasie',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_kasie']."</i>";
                            })
                    ->addColumn('p_tu',function($data){
                        $dat = (array) $data;
                        return $this->approvalAtasan($dat['kasubagtu']);
                    })
                    ->addColumn('k_tu',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_tu']."</i>";
                            })
                    ->addColumn('persetujuan',function($data) use($f_kasie,$f_tu)
                            {
                                $dat = (array) $data;
                                if($f_kasie && $f_tu)
                                {
                                    return "<ul>
                                    <li><small><strong>Kasie : </strong>".$this->approvalAtasan($dat['kasie'])."</small></li>
                                    <li><small><strong>Kasubag TU : </strong>".$this->approvalAtasan($dat['kasubagtu'])."</small></li>
                                    </ul>";                                   
                                }
                                else if($f_kasie)
                                    return $this->approvalAtasan($dat['kasie']);
                                else if ($f_tu)
                                    return $this->approvalAtasan($dat['kasubagtu']);
                                else
                                    {
                                        return "-";
                                    }

                            })
                    ->addColumn('keterangan',function($data) use($f_kasie,$f_tu)
                            {
                                $dat = (array) $data;
                                if($f_kasie && $f_tu)
                                {
                                    return "<ul>
                                    <li><small><strong>Kasie : </strong>".$dat['ket_kasie']."</small></li>
                                    <li><small><strong>Kasubag TU : </strong>".$dat['ket_tu']."</small></li>
                                    </ul>";                                   
                                }
                                else if($f_kasie)
                                    return $dat['ket_kasie'];
                                else if ($f_tu)
                                    return $dat['ket_tu'];
                                else
                                    {
                                        return "-";
                                    }

                            })
                    ->rawColumns(['tindakan','p_kasie','k_kasie','p_tu','k_tu','persetujuan','keterangan'])
                    ->make(true);
        }
        catch(Throwable $e)
        {
            report('Failed to load PLT asigment table ASN on '.$e);
            error_log('Failed to load PLT asigment table ASN on '.$e);
            //return "fail_plt_try_caught";
        }

    }

    public function createTablePLTAssignmentPJLP(Request $request)
    {
        $list_jabatan = Auth::user()->jabatan_plt;
        $jabatanController = new JabatanController();

        if(is_string($list_jabatan))
        {
            error_log("Fetch PLT Assignment PJLP data failure with message : ".$list_jabatan);
            report("Fetch PLT Assignment PJLP data failure with message : ".$list_jabatan);
            return null;
        }

        try{

            

            $base_query = DB::table("asigment_pjlp as a")->join('daftar_cuti_pjlp as d','a.no_cuti','=','d.id')
                        ->join('cuti_tahunan_pjlp as ct','d.nip','=','ct.nip')
                        ->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil');

            $base_query_raw = "asigment_pjlp as a inner join daftar_cuti_pjlp as d on a.no_cuti = d.id inner join cuti_tahunan_pjlp as ct on d.nip = ct. nip inner join data_pegawai as dp on d.nip = dp.nip inner join penempatan as p on dp.kode_penempatan = p.kode_panggil";
            
            
            $get_columns = [    
                                'dp.nip',
                                'dp.nama',
                                'p.penempatan',
                                'a.no_cuti',
                                'a.kasie',
                                'a.ket_kasie',
                                'a.kasubagtu',
                                'a.ket_tu',
                                'a.ppk',
                                'a.ket_ppk',
                                'd.jenis_cuti',
                                'd.alasan',
                                'd.tlpn',
                                'd.alamat',
                                'd.tgl_awal',
                                'd.tgl_akhir',
                                'd.total_cuti',
                                'd.tgl_pengajuan'
            ];

            // flag
            $f_kasie = false;
            $f_tu = false;
            $f_ppk = false;
            error_log(' start fetch data kasie plt with val : '.$jabatanController->is_user_plt_kasie());

            // fetch data kasie
            if($jabatanController->is_user_plt_kasie())
            {

                $list_j_kasie = $jabatanController->getJabatanPltKasie();
                $query_kasie = $base_query;

                error_log('fetch data kasie plt, value : '.implode('||',$list_j_kasie));
                if(count($list_j_kasie) == 1)
                {   
                    // single
                    $query_kasie = $query_kasie->where('dp.kasie','=',implode('||',$list_j_kasie))->get($get_columns);
                    error_log('fetch data kasie plt (single) , val : '.implode('||',$list_j_kasie));
                }
                else
                {
                    // multi
                    $query = "select ".implode(',',$get_columns)." from ".$base_query_raw;

                    $w = ' dp.kasie = '.array_pop($list_j_kasie);

                    foreach($list_j_kasie as $jk){
                        error_log('jk is '.$jk);
                        $w = $w.' or dp.kasie = '.$jk;
                    }
                    $query = $query .' where '. $w;

                    $query_kasie = DB::select($query);
                    // error_log($query_kasie);
                }


                if(count($query_kasie) > 0)
                    $f_kasie = true;
            }



            // fetch data tu
            if($jabatanController->is_user_plt_tu())
            {
                $query_tu = $base_query;

                $query_tu = $query_tu->where('a.kasie','=','s')->get($get_columns);

                if($query_tu->count() != 0)
                    $f_tu = true;
            }

            // fetch data ppk
            if($jabatanController->is_user_plt_ppk() || $jabatanController->is_user_plt_pptk())
            {
                error_log('Hit ppk pptk');
                $query_ppk = "select ".implode(',',$get_columns)." from ".$base_query_raw;

                $query_ppk = DB::select($query_ppk);

                if(count($query_ppk) > 0)
                    $f_ppk = true;
            }

            // create final query
            if($f_kasie && $f_tu && $f_ppk)
            {
                $fn_query = $query_tu->merge($query_kasie);
                $final_query = $fn_query->merge($query_ppk);
            }
            else if($f_kasie && $f_tu)
                $final_query = $query_tu->merge($query_kasie);
            else if($f_kasie && $f_ppk)
                $final_query = $query_ppk->merge($query_kasie);
            else if($f_ppk && $f_tu)
                $final_query = $query_tu->merge($query_ppk);
            else if($f_kasie)
                $final_query = $query_kasie;
            else if($f_tu)
                $final_query = $query_tu;
            else if($f_ppk)
                $final_query = $query_ppk;



            else
                $final_query = $base_query->where('dp.nip','=')->get($get_columns); // dummy query

            // create table for datatables
            return DataTables::of($final_query)
                    ->addIndexColumn()
                    ->addColumn('tindakan',function($row){
                        return '<p>';
                    })
                    ->addColumn('p_kasie',function($data){
                        $dat = (array) $data;
                        return $this->approvalAtasan($dat['kasie']);
                    })
                    ->addColumn('k_kasie',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_kasie']."</i>";
                            })
                    ->addColumn('p_tu',function($data){
                        $dat = (array) $data;
                        return $this->approvalAtasan($dat['kasubagtu']);
                    })
                    ->addColumn('k_tu',function($data)
                            {
                                $dat = (array) $data;
                                return "<i id='ket'>".$dat['ket_tu']."</i>";
                            })
                    ->addColumn('persetujuan',function($data) use($f_kasie,$f_tu,$f_ppk)
                            {
                                $dat = (array) $data;

                                if($f_kasie && $f_tu && $f_ppk)
                                {
                                    return "<ul>
                                    <li><small><strong>Kasie : </strong>".$this->approvalAtasan($dat['kasie'])."</small></li>
                                    <li><small><strong>PPK : </strong>".$this->approvalAtasan($dat['ppk'])."</small></li>
                                    <li><small><strong>Kasubag TU : </strong>".$this->approvalAtasan($dat['kasubagtu'])."</small></li>
                                    </ul>";
                                }
                                else if($f_kasie && $f_tu)
                                {
                                    return "<ul>
                                    <li><small><strong>Kasie : </strong>".$this->approvalAtasan($dat['kasie'])."</small></li>
                                    <li><small><strong>Kasubag TU : </strong>".$this->approvalAtasan($dat['kasubagtu'])."</small></li>
                                    </ul>";                                   
                                }
                                else if($f_kasie && $f_ppk)
                                {
                                    return "<ul>
                                    <li><small><strong>Kasie : </strong>".$this->approvalAtasan($dat['kasie'])."</small></li>
                                    <li><small><strong>PPK : </strong>".$this->approvalAtasan($dat['ppk'])."</small></li>
                                    </ul>";                                    
                                }
                                else if($f_tu && $f_ppk)
                                {
                                    return "<ul>
                                    <li><small><strong>PPK : </strong>".$this->approvalAtasan($dat['ppk'])."</small></li>
                                    <li><small><strong>Kasubag TU : </strong>".$this->approvalAtasan($dat['kasubagtu'])."</small></li>
                                    </ul>";
                                }
                                else if($f_kasie)
                                    return $this->approvalAtasan($dat['kasie']);
                                else if ($f_ppk)
                                    return $this->approvalAtasan($dat['ppk']);
                                else if ($f_tu)
                                    return $this->approvalAtasan($dat['kasubagtu']);
                                else
                                    {
                                        return "-";
                                    }

                            })
                    ->addColumn('keterangan',function($data) use($f_kasie,$f_tu,$f_ppk)
                    {
                        $dat = (array) $data;

                            if($f_kasie && $f_tu && $f_ppk)
                            {
                                return "<ul>
                                <li><small><strong>Kasie : </strong>".$dat['ket_kasie']."</small></li>
                                <li><small><strong>Kasubag TU : </strong>".$dat['ket_tu']."</small></li>
                                <li><small><strong>PPK : </strong>".$dat['ket_ppk']."</small></li>
                                </ul>";
                            }
                            else if($f_kasie && $f_tu)
                            {
                                return "<ul>
                                <li><small><strong>Kasie : </strong>".$dat['ket_kasie']."</small></li>
                                <li><small><strong>Kasubag TU : </strong>".$dat['ket_tu']."</small></li>
                                </ul>";                                   
                            }
                            else if($f_kasie && $f_ppk)
                            {
                                return "<ul>
                                <li><small><strong>Kasie : </strong>".$dat['ket_kasie']."</small></li>
                                <li><small><strong>PPK : </strong>".$dat['ket_ppk']."</small></li>
                                </ul>";                                    
                            }
                            else if($f_tu && $f_ppk)
                            {
                                return "<ul>
                                <li><small><strong>Kasubag TU : </strong>".$dat['ket_tu']."</small></li>
                                <li><small><strong>PPK : </strong>".$dat['ket_ppk']."</small></li>
                                </ul>";
                            }
                            else if($f_kasie)
                                return $dat['ket_kasie'];
                            else if ($f_ppk)
                                return $dat['ket_ppk'];
                            else if ($f_tu)
                                return $dat['ket_tu'];
                            else
                                {
                                    return "-";
                                }

                    })
                    ->rawColumns(['tindakan','p_kasie','k_kasie','p_tu','k_tu','persetujuan','keterangan'])
                    ->make();
        }
        catch(Throwable $e)
        {
            report('Failed to load PLT asigment table PJLP on '.$e);
            error_log('Failed to load PLT asigment table PJLP on '.$e);
            //return "fail_plt_try_caught";
        }

    }

    public function createTableUser(Request $request)
    {
        $query = DB::table('user')->get();

        if($request->ajax())
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('roles',function($data){
                $roles = explode('|',$data->level);
                $value ="";

                foreach($roles as $role)
                {
                    $value .= $role.'<br>';
                }

                return $value;
            })
            ->addColumn('rolesArray',function($data){
                return explode('|',$data->level);
            })
            ->rawColumns(['roles'])
            ->make(true);
    }
}
