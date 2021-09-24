<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// model utk tabel
use App\Models\Pegawai_ASN as ASN;
use App\Models\Pegawai_PJLP as PJLP;

// datatable utk pembuatan table melalui facade
use App\DataTables\Pegawai_ASNDataTable;
use App\DataTables\pegawaiDataTable;

class TabelController extends Controller
{
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

    public function createTableASN(Request $request)
    {
        $d = DB::table('data_pegawai')->where('golongan','!=','PJLP')->get();
        
        if($request->ajax())
        {
            error_log("Permintaan ASN : " . $request);
            return DataTables::of($d)
                ->addIndexColumn()
                ->make(true);
        }
        return view('dashboard/kepegawaian');
    }

    public function createTablePJLP(Request $request)
    {
        $d = DB::table('data_pegawai')->where('golongan','PJLP')->get();
        
        if($request->ajax())
        {
            error_log("Permintaan PJLP : " . $request);
            return DataTables::of($d)
                ->addIndexColumn()
                ->make(true);
        }
        return view('dashboard/kepegawaian');
        //return $d;
    }

    public function createTableAssignmentASN(Request $request)
    {
        $query = DB::table("asigment_asn",'a')->join('daftar_cuti_asn as d','a.no_cuti','=','d.id');

        // TODO : buat tampil tabel assignment asn untuk karu
        // TODO : buat tampil tabel assignment asn untuk kasubag tu
        if(in_array('KASIE',Auth::User()->roles))
        {
            $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                ->where('dp.kasie','=',Auth::user()->data['jabatan'])
                ->get([
                    'dp.nip',
                    'dp.nrk',
                    'dp.nama',
                    'a.no_cuti',
                    'a.kasie',
                    'a.ket_kasie',
                    'd.jenis_cuti',
                    'd.alasan',
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
        elseif(in_array('KATON',Auth::User()->roles))
        {

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
                    'd.tgl_awal',
                    'd.tgl_akhir',
                    'd.total_cuti',
                    'd.tgl_pengajuan'
                ]);

                $dt =  DataTables::of($query)
                        ->addIndexColumn()
                        ->addColumn('tindakan',function($row){
                                $deleteRoute = route('report.asn.delete',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                                $appRoute = route('report.asn.app',['nip'=>$row->nip,'no_cuti'=>$row->no_cuti]);
                                
                                $btn = '<a href="'.$appRoute.'" class="edit btn btn-info btn-sm">Ambil Surat Cuti</a>';
                                $btn = $btn.'<a href="'.$deleteRoute.'" class="edit btn btn-danger btn-sm">Hapus</a>'; 
                            return $btn;
                        })
                        ->rawColumns(['tindakan'])
                        ->make(true);
        }

        if($request->ajax())
        {
            return $dt; 
        }
        return view('dashboard/report');
    }

    public function createTableAssignmentPJLP(Request $request)
    {
        error_log("Data PJLP Start");
        $query = DB::table("asigment_pjlp",'a')->join('daftar_cuti_pjlp as d','a.no_cuti','=','d.id');

        if(in_array('KASIE',Auth::User()->roles))
        {
            //$query = $query->
        }
        elseif(in_array('KATON',Auth::User()->roles))
        {

        }
        elseif(in_array('KASUBAGTU',Auth::user()->roles))
        {

        }
        elseif(in_array('PPK',Auth::user()->roles))
        {

        }
        elseif(in_array('KASUDIN',Auth::user()->roles))
        {

        }
        elseif(Auth::user()->is_admin)
        {
            error_log('user is admin');
            $query = $query->join('data_pegawai as dp','d.nip','=','dp.nip')
                ->join('penempatan as p','dp.kode_penempatan','=','p.kode_panggil')
                ->get([
                    'dp.nip',
                    'dp.nama',
                    'p.penempatan',
                    'a.no_cuti',
                    'd.jenis_cuti',
                    'd.tgl_awal',
                    'd.tgl_akhir',
                    'd.total_cuti',
                    'd.tgl_pengajuan'
                ]);

                
        }

        if($request->ajax())
        {
            return DataTables::of($query)
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
        
        return view('dashboard/report');
    }

    public function createTableAssignmentSELF(Request $request)
    {
        if(in_array('ASN',Auth::user()->roles))
        {
            $tabel = DB::table('daftar_cuti_asn','d')->join('asigment_asn as a','d.id','=','a.no_cuti');
            $col = [
                'd.nip',
                'd.alasan',
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
                ->rawColumns(['tindakan'])
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

    public function createTablePegawai(pegawaiDataTable $dataTable)
    {
        return $dataTable->render('try');
    }
}
