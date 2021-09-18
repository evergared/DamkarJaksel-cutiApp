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
        $query = DB::table("asigment_asn")->all();
        if(in_array('KASIE',Auth::User()->roles))
        {

        }
        elseif(in_array('KATON',Auth::User()->roles))
        {

        }

        if($request->ajax())
        {
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        return view('dashboard/report');
    }

    public function createTableAssignmentPJLP(Request $request)
    {
        $query = DB::table("asigment_pjlp")->all();
        if($request->ajax())
        {
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        
        return view('dashboard/report');
    }

    public function createTableAssignmentSELF(Request $request)
    {
        if(in_array('ASN',Auth::user()->roles))
            $tabel = DB::table('daftar_cuti_asn','d')->join('asigment_asn as a','d.id','=','a.no_cuti');
        elseif(in_array('PJLP',Auth::user()->roles))
            $tabel = DB::table('daftar_cuti_pjlp','d')->join('asigment_pjlp as a','d.id','=','a.no_cuti');

        $query = $tabel->where('d.nip','=',Auth::user()->nip)
            ->get([
                'd.nip',
                'a.no_cuti',
                'd.jenis_cuti',
                'd.tgl_awal',
                'd.tgl_akhir',
                'd.total_cuti',
                'd.tgl_pengajuan'
            ]);
        
        if($request->ajax())
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
        
        return view('dashboard/report');
    }

    public function createTablePegawai(pegawaiDataTable $dataTable)
    {
        return $dataTable->render('try');
    }
}
