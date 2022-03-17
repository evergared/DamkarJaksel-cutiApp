<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Throwable;

// controller untuk i/o data jabatan dan plt
class JabatanController extends Controller{

    // list jabatan
    public $j_kasudin = '00';
    public $j_kasie = ['01','02','03','04','05','06','07','08','09','10','12','13','15'];
    public $j_ppk = '14';
    public $j_pptk = '14A';
    public $j_tu = '11';
    public $j_pjlp = ['16','17','18','19'];

    public function is_user_plt_kasie()
    {
        $list_jabatan = Auth::user()->jabatan_plt;
        if(is_string($list_jabatan))
            return false;
        else
        {
            $val = false;
            foreach($this->j_kasie as $j)
            {
                if($list_jabatan->contains($j))
                {
                    $val = true;
                    break;
                }
            }
            error_log('test trye : '.$val);
            return $val;
        }
            
    }

    public function is_user_plt_ppk()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return $list_jabatan->contains($this->j_ppk) || $list_jabatan->contains($this->getTrueJabatanPPK());
    }

    public function is_user_plt_pptk()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return $list_jabatan->contains($this->j_pptk);
    }

    public function is_user_plt_tu()
    {
        $list_jabatan = Auth::user()->jabatan_plt;

        if(is_string($list_jabatan))
            return false;
        else
            return $list_jabatan->contains($this->j_tu);
    }

    public function getKasieFromPenempatan($kode_penempatan)
    {
        $k = '-';

        switch($kode_penempatan)
        {
            case '41' : $k='-';break;
            case '411' : $k='01';break;
            case '412' : $k='02';break;
            case '413' : $k='03';break;
            case '414' : $k='04';break;
            case '415' : $k='05';break;
            case '416' : $k='06';break;
            case '417' : $k='07';break;
            case '418' : $k='08';break;
            case '419' : $k='09';break;
            case '420' : $k='10';break;
            case '41PL' : $k='12';break;
            case '41PN' : $k='15';break;
            case '41SA' : $k='13';break;
            case '41TU' : $k='11';break;
            default : $k = '-';break;
        }

        return $k;
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
                error_log('j is '.$j);
                if(in_array($j, $this->j_kasie))
                {
                    error_log('get jabatan kasie got kasie value : '.$j);
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

    public function getTrueJabatanPPK()
    {
        $nip_ppk = DB::table('jabatan')->where('no','=',$this->j_ppk)->value('keterangan');
        return DB::table('data_pegawai')->where('nip','=',$nip_ppk)->value('jabatan');
    }

    public function getAssignedPPKandPPTK()
    {
        try{

    
            $nip_ppk = DB::table('jabatan')->where('no','=',$this->j_ppk)->value('keterangan');
            $nip_pptk = DB::table('jabatan')->where('no','=',$this->j_pptk)->value('keterangan');
            $nama_ppk = '-';
            $nama_pptk = '-';

            if(!is_null($nip_ppk))
            {
                $nama_ppk = DB::table('data_pegawai')->where('nip','=',$nip_ppk)->value('nama');
            }
            else
            {
                $nip_ppk = '-';
                $nama_ppk = '-';
            }
            
            if(!is_null($nip_pptk))
            {
                $nama_pptk = DB::table('data_pegawai')->where('nip','=',$nip_pptk)->value('nama');
            }
            else
            {
                $nip_pptk = '-';
                $nama_pptk = '-';
            }

            return [$nip_ppk, $nama_ppk, $nip_pptk, $nama_pptk];
    
        }

        catch(Throwable $e)
        {
            error_log('get list ppk pptk error : '.$e);
        }

    }

    public function getArrayJabatan(Request $request)
    {
        return DB::table('jabatan')->get(['no as value','jabatan as text'])->toArray();
    }

    public function addJabatan(Request $r)
    {
        try{

            $no = $r->input('id');
            $jabatan = $r->input('kode_jabatan');

            $q = DB::table('jabatan');

            if($q->where('no','=',$no)->exists())
            return 'fail_add_jabatan_exist';
            
            $q->insert([
                'no' => $no,
                'jabatan' => $jabatan
            ]);

            return 'success_add_jabatan';
        }
        catch(Throwable $e)
        {
            error_log('Error to add Jabatan on id '.$no.' with message : '.$e);
            report('Error to add Jabatan on id '.$no.' with message : '.$e);
            return 'fail_add_jabatan_try_caught';
        }
    }

    public function addPLT(Request $r)
    {
        try{

            $nip = $r->input('nip');
            $jabatan = $r->input('kode_jabatan');

            $q = DB::table('plt');

            if($q->where('nip_pelaksana','=',$nip)->where('kode_jabatan','=',$jabatan)->exists())
            return 'fail_add_plt_exist';
            
            $q->insert([
                'nip_pelaksana' => $nip,
                'kode_jabatan' => $jabatan
            ]);

            DB::table('user')->where('nip','=',$nip)->update(['is_plt' => true]);

            return 'success_add_plt';
        }
        catch(Throwable $e)
        {
            error_log('Error to add PLT on '.$nip.' with message : '.$e);
            report('Error to add PLT on '.$nip.' with message : '.$e);
            return 'fail_add_plt_try_caught';
        }
    }

    public function setAssignedPPKandPPTK(Request $r)
    {
        try{

            $nip = $r->input('nip');
            $ppk = $r->input('ppk');

            if($ppk)
                $jabatan = $this->j_ppk;
            else
                $jabatan = $this->j_pptk;

            $table = DB::table('jabatan');

            $current = $table->where('no','=',$jabatan)->value('keterangan');

            if(is_null($nip) && is_null($current))
                return 'fail_add_ppk-pptk_both_null';

            if($nip === $current)
                return 'fail_add_ppk-pptk_exist';
            
            $table->where('no','=',$jabatan)->update(['keterangan' => $nip]);

            return 'success_add_ppk-pptk';
        }
        catch(Throwable $e)
        {
            error_log('Error to add ppk-pptk on '.$nip.' with message : '.$e);
            report('Error to add ppk-pptk on '.$nip.' with message : '.$e);
            return 'fail_add_ppk-pptk_try_caught';
        }
    }

    public function deleteJabatan(Request $request)
    {
        // TODO : buat alert untuk memberitahu ada user yang terpengaruh
        try{

            $no = $request->input('no');
            $table = DB::table('jabatan');
            if($table->where('no','=',$no)->exists())
            {
                $table->where('no','=',$no)->delete();
                return 'success_delete_jabatan';
            }
            else
            {
                return 'fail_delete_jabatan_exist';
            }

        }
        catch(Throwable $e)
        {
            error_log('Failed to delete data jabatan on '.$no.' error : '.$e);
            report('Failed to delete data jabatan on '.$no.' error : '.$e);
            return 'fail_delete_jabatan_try_caught';
        }
    }

    public function deletePLT(Request $r)
    {
        try{
            $nip = $r->input('nip');
            $kj = $r->input('kode_jabatan');

            $table = DB::table('plt');
            if($table->where('nip_pelaksana','=',$nip)->where('kode_jabatan','=',$kj)->exists())
            {
                $table->where('nip_pelaksana','=',$nip)->where('kode_jabatan','=',$kj)->delete();

                if(!DB::table('plt')->where('nip_pelaksana','=',$nip)->exists())
                    DB::table('user')->where('nip','=',$nip)->update(['is_plt' => false]);
                        
                return 'success_delete_plt';
            }
            else
            {
                return 'fail_delete_plt_exist';
            }

        }
        catch(Throwable $e)
        {
            report('Failed to delete PLT data for nip '.$nip.' with kode_jabatan '.$kj.' on '.$e);
            error_log('Failed to delete PLT data for nip '.$nip.' with kode_jabatan '.$kj.' on '.$e);
            return 'fail_delete_plt_try_caught';
        }
    }

}
?>