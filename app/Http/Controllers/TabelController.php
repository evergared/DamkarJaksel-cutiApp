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
            $tabel = DB::table('daftar_cuti_asn','d')->join('asigment_asn','d.nip','=','asigment_asn.nip');
        elseif(in_array('PJLP',Auth::user()->roles))
            $tabel = DB::table('daftar_cuti_pjlp','d')->join('asigment_pjlp','d.nip','=','asigment_pjlp.nip');

        $query = $tabel->where('d.nip','=',Auth::user()->nip)->get();
        
        if($request->ajax())
        {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tindakan',function($row){
                            $btn = '<a href="javascript:void(0)" class="edit btn btn-info btn-sm">View</a>';
                            $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                            $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Delete</a>';
         
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
