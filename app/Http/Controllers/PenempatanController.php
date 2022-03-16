<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Throwable;

class PenempatanController extends Controller
{
    public function addPenempatan(Request $r)
    {
        try{

            $id = $r->input('id');
            $penempatan = $r->input('penempatan');
            $kecamatan = $r->input('kecamatan');

            $q = DB::table('penempatan');

            if($q->where('kode_panggil','=',$id)->exists())
            return 'fail_add_penempatan_exist';
            
            $q->insert([
                'kode_panggil' => $id,
                'penempatan' => $penempatan,
                'kecamatan' => $kecamatan
            ]);

            return 'success_add_penempatan';
        }
        catch(Throwable $e)
        {
            error_log('Error to add penempatan on '.$id.' with message : '.$e);
            report('Error to add penempatan on '.$id.' with message : '.$e);
            return 'fail_add_penempatan_try_caught';
        }
    }

    public function deletePenempatan(Request $request)
    {
        // TODO : buat alert untuk memberitahu ada user yang terpengaruh
        try{

            $kd = $request->input('kode_panggil');
            $table = DB::table('penempatan');
            if($table->where('kode_panggil','=',$kd)->exists())
            {
                $table->where('kode_panggil','=',$kd)->delete();
                return 'success_delete_penempatan';
            }
            else
            {
                return 'fail_delete_penempatan_exist';
            }

        }
        catch(Throwable $e)
        {
            error_log('Failed to delete data penempatan on '.$kd.' error : '.$e);
            report('Failed to delete data penempatan on '.$kd.' error : '.$e);
            return 'fail_delete_penempatan_try_caught';
        }
    }
}
?>