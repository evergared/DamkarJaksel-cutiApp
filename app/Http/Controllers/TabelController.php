<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

// model utk tabel
use App\Models\Pegawai_ASN as ASN;
use App\Models\Pegawai_PJLP as PJLP;

// datatable utk pembuatan table melalui facade
use App\DataTables\Pegawai_ASNDataTable;

class TabelController extends Controller
{
    public function createTableASN(Request $request)
    {
        $d = ASN::latest()->get();
        
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
        $d = PJLP::latest()->get();
        
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
}