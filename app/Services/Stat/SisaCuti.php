<?php

namespace App\Services\Stat;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SisaCuti
{
    public static   $sisaTahunan = 0,
                    $n1 = 0,
                    $n2 = 0,
                    $pakai = 0,
                    $flag = '';                    

    static $user;

    public function __construct()
    {
        SisaCuti::$user = Auth::user();        
        $this->fetchData();

        if(SisaCuti::$user->is_asn)
        {
            SisaCuti::$pakai = SisaCuti::$n2;
            SisaCuti::$flag = '2';
            if(SisaCuti::$n2 < 1)
            {
                SisaCuti::$pakai = SisaCuti::$n1;
                SisaCuti::$flag = '1';
            }
            if(SisaCuti::$n1 < 1)
            {
                SisaCuti::$pakai = SisaCuti::$sisaTahunan;
                SisaCuti::$flag = '0';
            }
        }
        else 
            {
                SisaCuti::$pakai = SisaCuti::$sisaTahunan;
                SisaCuti::$flag = '0';
            }

error_log('sisa cuti flag: '.SisaCuti::$flag);
        // TODO : tambah event pada saat persetujuan cuti agar data yg tampil bisa realtime
    }

    public function fetchData()
    {
        if(SisaCuti::$user->is_pjlp)
        {
            $ts = (array) DB::table('cuti_tahunan_pjlp')
                                        ->where('nip','=',SisaCuti::$user->data['nip'])
                                        ->get('sisa')
                                        ->first();
            
                                        SisaCuti::$sisaTahunan = $ts['sisa'];
        }
        else
        {
            $tb = (array) DB::table('cuti_tahunan_asn')
                    ->where('nip','=',SisaCuti::$user->data['nip'])
                    ->get('sisa','n1','n2')
                    ->first();

           if(array_key_exists('sisa',$tb))
            SisaCuti::$sisaTahunan = $tb['sisa'];

           if(array_key_exists('n1',$tb))
            SisaCuti::$n1 = $tb['n1'];

           if(array_key_exists('n2',$tb))
            SisaCuti::$n2 = $tb['n2'];
        } 
    }
}

?>