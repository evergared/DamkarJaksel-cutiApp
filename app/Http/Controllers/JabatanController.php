<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Throwable;

// controller untuk i/o data jabatan dan plt
class JabatanController extends Controller{

    // list jabatan
    public $j_kasie = ['01','02','03','04','05','06','07','08','09','10','12','13','15'];
    public $j_ppk = '14';
    public $j_pptk = '14A';
    public $j_tu = '11';

    public function is_user_plt_kasie()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return in_array($this->j_kasie, $list_jabatan);
    }

    public function is_user_plt_ppk()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return in_array($this->j_ppk, $list_jabatan);
    }

    public function is_user_plt_pptk()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return in_array($this->j_pptk, $list_jabatan);
    }

    public function is_user_plt_tu()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return in_array($this->j_tu, $list_jabatan);
    }

    public function getUserPltTag()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
        {
            error_log("Fetch PLT data for nip ".Auth::user()->nip." failure with message : ".$list_jabatan);
            report("Fetch PLT data for nip ".Auth::user()->nip." failure with message : ".$list_jabatan);
            return [];
        }

        try{

            $kasie = false;
            $ppk = false;
            $tu = false;
    
            foreach($list_jabatan as $j)
            {
                if(in_array($j,$this->j_kasie))
                {
                    if(!$kasie) $kasie = true;
                }
    
                if($j === $this->j_ppk)
                {
                    if(!$ppk) $ppk = true;
                }
    
                if($j === $this->tu)
                {
                    if(!$tu) $tu = true;
                }
            }
    
            $tag = [];
    
            if($kasie) array_push($tag,'k');
            if($ppk) array_push($tag,'p');
            if($tu) array_push($tag,'t');
    
            return $tag;

        }
        catch(Throwable $e)
        {
            report('Failed to load PLT data for nip '.Auth::user()->nip.' on '.$e);
            error_log('Failed to load PLT data for nip '.Auth::user()->nip.' on '.$e);
            return [];
        }

    }

    public function getJabatanPltKasie()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
        {
            error_log("Fetch PLT kasie data for nip ".Auth::user()->nip." failure with message : ".$list_jabatan);
            report("Fetch PLT kasie data for nip ".Auth::user()->nip." failure with message : ".$list_jabatan);
            return [];
        }

        try{

            $list = [];

            foreach($list_jabatan as $j)
            {
                if(in_array($j, $this->j_kasie))
                {
                    array_push($list,$j);
                }
            }

            return $list;

        }
        catch(Throwable $e)
        {
            report('Failed to load PLT kasie data for nip '.Auth::user()->nip.' on '.$e);
            error_log('Failed to load PLT kasie data for nip '.Auth::user()->nip.' on '.$e);
            return [];
        }
    }

}
?>