<?php

namespace App\Services\Stat;

use App\Models\asigment_asn;
use App\Models\asigment_pjlp;
use App\Models\daftar_cuti_asn;
use App\Models\daftar_cuti_pjlp;
use App\Models\data_pegawai;
use Illuminate\Database\Eloquent\Builder;
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
                                ->where('kasubagtu','!=','s')
                                ->count();
                $waitPJLP = asigment_pjlp::where('kasie','!=','s')
                                ->where('kasubagtu','!=','s')
                                ->where('ppk','!=','s')
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


                $approvedASN = data_pegawai::whereHas('daftar_cuti_asn.asigment_asn',
                            function(Builder $query){
                                $query->where('kasie','=','s');
                                })
                            ->where('kasie','=',Auth::user()->jabatan)
                            ->count();
                $approvedPJLP = data_pegawai::whereHas('daftar_cuti_pjlp.asigment_pjlp',
                            function(Builder $query){
                                $query->where('kasie','=','s');
                                })
                            ->where('kasie','=',Auth::user()->jabatan)
                            ->count();
                Cards::$approved = $approvedASN + $approvedPJLP;


                $waitASN = data_pegawai::whereHas('daftar_cuti_asn.asigment_asn',
                            function(Builder $query){
                                $query->where('kasie','!=','s');
                                })
                            ->where('kasie','=',Auth::user()->jabatan)
                            ->count();
                $waitPJLP = data_pegawai::whereHas('daftar_cuti_pjlp.asigment_pjlp',
                            function(Builder $query){
                                $query->where('kasie','!=','s');
                                })
                            ->where('kasie','=',Auth::user()->jabatan)
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


                $totalASN = data_pegawai::has('daftar_cuti_asn')
                            ->count();
                $totalPJLP = data_pegawai::has('daftar_cuti_pjlp')
                            ->count();
                Cards::$total = $totalASN + $totalPJLP;


                $approvedASN = data_pegawai::whereHas('daftar_cuti_asn.asigment_asn',
                            function(Builder $query){
                                $query->where('kasubagtu','=','s');
                                })
                            ->count();
                $approvedPJLP = data_pegawai::whereHas('daftar_cuti_pjlp.asigment_pjlp',
                            function(Builder $query){
                                $query->where('kasubagtu','=','s');
                                })
                            ->count();
                Cards::$approved = $approvedASN + $approvedPJLP;


                $waitASN = data_pegawai::whereHas('daftar_cuti_asn.asigment_asn',
                            function(Builder $query){
                                $query->where('kasubagtu','!=','s');
                                })
                            ->count();
                $waitPJLP = data_pegawai::whereHas('daftar_cuti_pjlp.asigment_pjlp',
                            function(Builder $query){
                                $query->where('kasubagtu','!=','s');
                                })
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
                                ->where('kasubagtu','!=','s')
                                ->count();
                $waitPJLP = asigment_pjlp::where('kasie','!=','s')
                                ->where('kasubagtu','!=','s')
                                ->where('ppk','!=','s')
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


                $totalPJLP = data_pegawai::has('daftar_cuti_pjlp')
                            ->count();
                Cards::$total = $totalPJLP;


                $approvedPJLP = data_pegawai::whereHas('daftar_cuti_pjlp.asigment_pjlp',
                            function(Builder $query){
                                $query->where('ppk','=','s');
                                })
                            ->count();
                Cards::$approved = $approvedPJLP;


                $waitPJLP = data_pegawai::whereHas('daftar_cuti_pjlp.asigment_pjlp',
                            function(Builder $query){
                                $query->where('ppk','!=','s');
                                })
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

                    Cards::$wait = daftar_cuti_pjlp::whereHas('asigment_pjlp',function(Builder $query){
                                        $query->where('kasie','!=','s')
                                            ->where('kasubagtu','!=','s')
                                            ->where('ppk','!=','s');
                                    })->where('nip','=',Auth::user()->nip)
                                    ->count();

                    Cards::$approved = daftar_cuti_pjlp::whereHas('asigment_pjlp',function(Builder $query){
                                        $query->where('kasie','=','s')
                                            ->where('kasubagtu','=','s')
                                            ->where('ppk','=','s');
                                    })->where('nip','=',Auth::user()->nip)
                                    ->count();
                }
                else
                {
                    Cards::$total = daftar_cuti_asn::where('nip','=',Auth::user()->nip)->count();

                    Cards::$wait = daftar_cuti_asn::has('asigment_asn',function(Builder $query){
                                        $query->where('kasie','!=','s')
                                            ->where('kasubagtu','!=','s');
                                    })->where('nip','=',Auth::user()->nip)
                                    ->count();
                    
                    Cards::$approved = daftar_cuti_asn::has('asigment_asn',function(Builder $query){
                                        $query->where('kasie','=','s')
                                            ->where('kasubagtu','=','s');
                                    })->where('nip','=',Auth::user()->nip)
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