<?php

namespace App\Http\Controllers;

use App\Models\Pegawai_ASN;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Pegawai_ASNDataTable;

class PegawaiASNController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pegawai_ASNDataTable $dataTable)
    {
        return $dataTable->render('dashboard/kepegawaian');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pegawai_ASN  $pegawai_ASN
     * @return \Illuminate\Http\Response
     */
    public function show(Pegawai_ASN $pegawai_ASN)
    {
        return $pegawai_ASN->all();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pegawai_ASN  $pegawai_ASN
     * @return \Illuminate\Http\Response
     */
    public function edit(Pegawai_ASN $pegawai_ASN)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pegawai_ASN  $pegawai_ASN
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pegawai_ASN $pegawai_ASN)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pegawai_ASN  $pegawai_ASN
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pegawai_ASN $pegawai_ASN)
    {
        //
    }
}
