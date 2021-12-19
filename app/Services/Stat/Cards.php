<?php

namespace App\Services\Stat;

use App\Models\asigment_asn;
use App\Models\asigment_pjlp;
use App\Models\daftar_cuti_asn;
use App\Models\daftar_cuti_pjlp;
use App\Models\data_pegawai;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Cards
{
    // TODO : gunakan event agar data yang ditampilkan bisa realtime
    // TODO : queue asynchronous, kemungkinan data yg di fetch besar

    // Data
    public static    $total = "mendata...", 
                     $approved = "mendata...", 
                     $wait = "mendata...", 
                     $persentase = "mendata...",

    // Caption
                    $cTotal = 'Total pengajuan cuti',
                    $cApproved = 'Total cuti yang telah disetujui',
                    $cWait = 'Total cuti yang menunggu persetujuan',
                    $cPersentase = 'Persentase cuti yang telah disetujui';

    public function __construct()
    {
        $this->fetchData();
    }

    public static function fetchData()
    {

        try
        {

            if(Auth::user()->is_admin)
            {
                Cards::$cTotal = 'Total cuti seluruh pegawai JS';


                $totalASN = asigment_asn::count();
                $totalPJLP = asigment_pjlp::count();
                Cards::$total = $totalASN + $totalPJLP;


                $approvedASN = asigment_asn::where('kasie','=','s')
                                ->where('kasubagtu','=','s')
                                ->count();
                $approvedPJLP = asigment_pjlp::where('kasie','=','s')
                                ->where('kasubagtu','=','s')
                                ->where('ppk','=','s')
                                ->count();
                Cards::$approved = $approvedASN + $approvedPJLP;


                $waitASN = asigment_asn::where('kasie','!=','s')
                                ->orWhere('kasubagtu','!=','s')
                                ->count();
                $waitPJLP = asigment_pjlp::where('kasie','!=','s')
                                ->orWhere('kasubagtu','!=','s')
                                ->orWhere('ppk','!=','s')
                                ->count();
                Cards::$wait = $waitASN + $waitPJLP;


                if(Cards::$approved != 0 && Cards::$total != 0)
                    Cards::$persentase = number_format((Cards::$approved / Cards::$total) * 100,2,',');
                else
                    Cards::$persentase = 0;
            }
            else if(Auth::user()->is_kasie)
            {
                Cards::$cTotal = 'Total ajuan cuti yang diterima';


                $totalASN = data_pegawai::has('daftar_cuti_asn')
                            ->where('kasie','=',Auth::user()->jabatan)
                            ->count();
                $totalPJLP = data_pegawai::has('daftar_cuti_pjlp')
                            ->where('kasie','=',Auth::user()->jabatan)
                            ->count();
                Cards::$total = $totalASN + $totalPJLP;


                $approvedASN = DB::table('asigment_asn as a')
                            ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                            ->join('data_pegawai as dp','d.nip','=','dp.nip')
                            ->where('dp.kasie','=',Auth::user()->jabatan)
                            ->where('a.kasie','=','s')
                            ->count();
                $approvedPJLP = DB::table('asigment_pjlp as a')
                            ->join('daftar_cuti_pjlp as d', 'a.no_cuti','=','d.id')
                            ->join('data_pegawai as dp','d.nip','=','dp.nip')
                            ->where('dp.kasie','=',Auth::user()->jabatan)
                            ->where('a.kasie','=','s')
                            ->count();
                Cards::$approved = $approvedASN + $approvedPJLP;


                $waitASN = DB::table('asigment_asn as a')
                            ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                            ->join('data_pegawai as dp','d.nip','=','dp.nip')
                            ->where('dp.kasie','=',Auth::user()->jabatan)
                            ->where('a.kasie','!=','s')
                            ->count();
                $waitPJLP = DB::table('asigment_pjlp as a')
                            ->join('daftar_cuti_pjlp as d', 'a.no_cuti','=','d.id')
                            ->join('data_pegawai as dp','d.nip','=','dp.nip')
                            ->where('dp.kasie','=',Auth::user()->jabatan)
                            ->where('a.kasie','!=','s')
                            ->count();
                Cards::$wait = $waitASN + $waitPJLP;


                if(Cards::$approved != 0 && Cards::$total != 0)
                    Cards::$persentase = number_format((Cards::$approved / Cards::$total) * 100,2,',');
                else
                    Cards::$persentase = 0;
            }
            else if(Auth::user()->is_kasubag_tu)
            {
                Cards::$cTotal = 'Total ajuan cuti yang diterima';


                $totalASN = DB::table('asigment_asn as a')  // pegawai non TU
                            ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                            ->join('data_pegawai as dp','d.nip','=','dp.nip')
                            ->where('dp.atasan','!=',Auth::user()->jabatan)
                            ->where('a.kasie','=','s')
                            ->count()
                            +
                            DB::table('asigment_asn as a')  // pegawai TU
                            ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                            ->join('data_pegawai as dp','d.nip','=','dp.nip')
                            ->where('dp.atasan','=',Auth::user()->jabatan)
                            ->count();
                $totalPJLP = DB::table('asigment_pjlp as a')
                            ->join('daftar_cuti_pjlp as d', 'a.no_cuti','=','d.id')
                            ->where('a.kasie','=','s')
                            ->count();
                Cards::$total = $totalASN + $totalPJLP;


                $approvedASN = DB::table('asigment_asn as a')
                            ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                            ->where('a.kasubagtu','=','s')
                            ->count();
                $approvedPJLP = DB::table('asigment_pjlp as a')
                            ->join('daftar_cuti_pjlp as d', 'a.no_cuti','=','d.id')
                            ->where('a.kasubagtu','=','s')
                            ->count();
                Cards::$approved = $approvedASN + $approvedPJLP;


                $waitASN = DB::table('asigment_asn as a')  // pegawai non TU
                        ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                        ->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->where('dp.atasan','!=',Auth::user()->jabatan)
                        ->where('a.kasie','=','s')
                        ->where('a.kasubagtu','!=','s')
                        ->count()
                        +
                        DB::table('asigment_asn as a')  // pegawai TU
                        ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                        ->join('data_pegawai as dp','d.nip','=','dp.nip')
                        ->where('dp.atasan','=',Auth::user()->jabatan)
                        ->where('a.kasubagtu','!=','s')
                        ->count();
                $waitPJLP = DB::table('asigment_asn as a')
                        ->join('daftar_cuti_asn as d', 'a.no_cuti','=','d.id')
                        ->where('a.kasie','=','s')
                        ->where('a.kasubagtu','!=','s')
                        ->count();
                Cards::$wait = $waitASN + $waitPJLP;


                if(Cards::$approved != 0 && Cards::$total != 0)
                    Cards::$persentase = number_format((Cards::$approved / Cards::$total) * 100,2,',');
                else
                    Cards::$persentase = 0;
            }
            else if(Auth::user()->is_kasudin)
            {
                Cards::$cTotal = 'Total cuti seluruh pegawai JS';


                $totalASN = asigment_asn::count();
                $totalPJLP = asigment_pjlp::count();
                Cards::$total = $totalASN + $totalPJLP;


                $approvedASN = asigment_asn::where('kasie','=','s')
                                ->where('kasubagtu','=','s')
                                ->count();
                $approvedPJLP = asigment_pjlp::where('kasie','=','s')
                                ->where('kasubagtu','=','s')
                                ->where('ppk','=','s')
                                ->count();
                Cards::$approved = $approvedASN + $approvedPJLP;


                $waitASN = asigment_asn::where('kasie','!=','s')
                                ->orWhere('kasubagtu','!=','s')
                                ->count();
                $waitPJLP = asigment_pjlp::where('kasie','!=','s')
                                ->orWhere('kasubagtu','!=','s')
                                ->orWhere('ppk','!=','s')
                                ->count();
                Cards::$wait = $waitASN + $waitPJLP;


                if(Cards::$approved != 0 && Cards::$total != 0)
                    Cards::$persentase = number_format((Cards::$approved / Cards::$total) * 100,2,',');
                else
                    Cards::$persentase = 0;
            }
            else if(Auth::user()->is_ppk)
            {
                Cards::$cTotal = 'Total ajuan cuti yang diterima';


                $totalPJLP = daftar_cuti_pjlp::count();
                Cards::$total = $totalPJLP;


                $approvedPJLP = daftar_cuti_pjlp::where('kasie','=','s')
                                ->where('kasubagtu','=','s')
                                ->where('ppk','=','s')
                                ->count();
                Cards::$approved = $approvedPJLP;


                $waitPJLP = daftar_cuti_pjlp::where('kasie','!=','s')
                            ->orWhere('kasubagtu','!=','s')
                            ->orWhere('ppk','!=','s')
                            ->count();
                Cards::$wait = $waitPJLP;


                if(Cards::$approved != 0 && Cards::$total != 0)
                    Cards::$persentase = number_format((Cards::$approved / Cards::$total) * 100,2,',');
                else
                    Cards::$persentase = 0;
            }
            else
            {
                // Untuk pribadi
                
                Cards::$cTotal = 'Total cuti yang diajukan';

                if(Auth::user()->is_pjlp)
                {
                    Cards::$total = daftar_cuti_pjlp::where('nip','=',Auth::user()->nip)->count();

                    Cards::$wait = DB::table('asigment_pjlp as a')
                                    ->join('daftar_cuti_pjlp as d','a.no_cuti','=','d.id')
                                    ->where('d.nip','=',Auth::user()->nip)
                                    ->where(function($q){
                                        $q->where('a.kasie','!=','s')
                                        ->orWhere('a.kasubagtu','!=','s')
                                        ->orWhere('a.ppk','!=','s');
                                    })
                                    ->count();

                    Cards::$approved = DB::table('asigment_pjlp as a')
                                    ->join('daftar_cuti_pjlp as d','a.no_cuti','=','d.id')
                                    ->where('d.nip','=',Auth::user()->nip)
                                    ->where(function($q){
                                        $q->where('a.kasie','=','s')
                                        ->where('a.kasubagtu','=','s')
                                        ->where('a.ppk','=','s');
                                    })
                                    ->count();
                }
                else
                {
                    Cards::$total = daftar_cuti_asn::where('nip','=',Auth::user()->nip)->count();

                    Cards::$wait = DB::table('asigment_asn as a')
                                    ->join('daftar_cuti_asn as d','a.no_cuti','=','d.id')
                                    ->where('d.nip','=',Auth::user()->nip)
                                    ->where(function($q){
                                        $q->where('a.kasie','!=','s')
                                        ->orWhere('a.kasubagtu','!=','s');
                                    })
                                    ->count();

                    Cards::$approved = DB::table('asigment_asn as a')
                                    ->join('daftar_cuti_asn as d','a.no_cuti','=','d.id')
                                    ->where('d.nip','=',Auth::user()->nip)
                                    ->where(function($q){
                                        $q->where('a.kasie','=','s')
                                        ->where('a.kasubagtu','=','s');
                                    })
                                    ->count();
                }

                if(Cards::$approved != 0 && Cards::$total != 0)
                    Cards::$persentase = number_format((Cards::$approved / Cards::$total) * 100,2,',');
                else
                    Cards::$persentase = 0;

            }

        }

        catch(Throwable $e)
        {
            error_log("Gagal fetch data untuk card header, error : ".$e);
            report("Gagal fetch data untuk card header. Exception : ".$e);

            Cards::$total = "----";
            Cards::$approved = "----";
            Cards::$wait = "----";
            Cards::$persentase = "----";
        }

        
    }
}

?>