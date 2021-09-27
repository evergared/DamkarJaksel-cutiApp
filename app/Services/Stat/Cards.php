<?php

namespace App\Services\Stat;

use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cards
{
    // TODO : gunakan event agar data yang ditampilkan bisa realtime
    // TODO : queue asynchronous, kemungkinan data yg di fetch besar

    protected static $user,
                     $total = "mendata...", 
                     $approved = "mendata...", 
                     $wait = "mendata...", 
                     $persentase = "mendata...";

    public function __construct()
    {
        $this->user = Auth::user();

        $this->fetchData();
    }

    public static function fetchData()
    {
        $table = DB::table('data_pegawai');
        $array_cuti = [];

        try
        {
            if(Cards::$user->is_kasie)
            {
                $bawahan = [];
                
                if($dt = $table->where('atasan','=',Cards::$user->data['jabatan'])->get('nip','jabatan'))
                {
                    $bawahan.array_push((array)$dt);
                    $karu = [];

                    foreach($bawahan as $katon)
                    {
                        if($dt = $table->where('atasan','=',$katon['jabatan'])->get('nip','jabatan'))
                        {
                            $karu.array_push((array)$dt);
                        }
                    }

                    if(count($karu) > 0)
                    {
                        $bawahan.array_merge($karu);
                        $anggota = [];

                        foreach($karu as $k)
                        {
                            if($dt = $table->where('atasan','=',$k['jabatan'])->get('nip','jabatan'))
                            {
                                $anggota.array_push((array)$dt);
                            }
                        }

                        if(count($anggota) > 0)
                        {
                            $bawahan.array_merge($anggota);   
                        }
                    }
                }

                if(count($bawahan) > 0)
                {
                    
                }
            }
        }

        catch(Throwable $e)
        {
            error_log("Gagal fetch data untuk card header");
            report("Gagal fetch data untuk card header. Exception : ".$e);

            Cards::$total = "----";
            Cards::$approved = "----";
            Cards::$wait = "----";
            Cards::$persentase = "----";
        }

        
    }
}

?>