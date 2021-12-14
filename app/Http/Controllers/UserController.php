<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index');
    }

    public function addUser(Request $request)
    {
        try{
            $nip = $request->input['nip'];
            $password = $request->input['password'];
            $email = $request->input['email'];
            $bukanPegawai = $request->input['bukanPegawai'];
            $nama = $request->input['nama'];
            $peran = $request->input['peran'];
            $penempatan = $request->input['nip'];

            DB::table('user')->insertOrIgnore([
                'nip' => $nip,
                'level' => implode('|',$peran),
                'password' => $password,
                'email' => $email
            ]);

            if($bukanPegawai)
            {
                if(in_array('PJLP',$peran))
                {
                    $golongan = "PJLP";
                    $Jabatan = 16;
                }
                else if(in_array('ASN',$peran))
                {
                    $golongan = "ASN";
                    if(in_array('KASIE',$peran)){
                        $Jabatan = $this->getJabatanKasieFromPenempatan($penempatan);
                    }
                    else if(in_array('KASIE.PENCEGAHAN',$peran)){
                        $Jabatan = 12;
                    }
                    else if(in_array('KASUBAGTU',$peran)){
                        $Jabatan = 11;
                    }
                    else if(in_array('KASUDIN',$peran)){
                        $Jabatan = 15;
                    }

                    

                }
                DB::table('data_pegawai')->insertOrIgnore([
                    'nip' => $nip,
                    'nrk' => $nip,
                    'nama' => $nama,
                    'golongan' => $golongan,
                    'jabatan' => $Jabatan,
                    'kode_penempatan' => $penempatan
                ]);
            }

            return 'success_add_user';
        }
        catch(Throwable $e){
            report('Add user error : '.$e);
            error_log('Add user error : '.$e);
            return 'fail_add_user_try_caught';
        }
    }

    public function changePassword(Request $request)
    {
        try{
            error_log('change password route hit');
            $nip = $request->input('nip');
            $pass = $request->input('pass');
            error_log('change passord input hit');
            $user = DB::table('user')->where('nip','=',$nip);
            $user->update(['password' => Hash::make($pass)]);

            return 'success_change_password';
        }
        catch(Throwable $e)
        {
            error_log('Password Change Error on Nip '.$nip.'! error : '.$e);
            report('Password Change Error on Nip '.$nip.'! error : '.$e);
            return 'fail_change_password_try_caught';
        }   
    }

    public function getArrayPenempatan(Request $request)
    {
        return DB::table('penempatan')->get(['kode_panggil as value','penempatan as text'])->toArray();
    }

    public function getJabatanKasieFromPenempatan($kode_penempatan)
    {
        switch($kode_penempatan)
        {
            case 411: return 1;break;
            case 412: return 2;break;
            case 413: return 3;break;
            case 414: return 4;break;
            case 415: return 5;break;
            case 416: return 6;break;
            case 417: return 7;break;
            case 418: return 8;break;
            case 419: return 9;break;
            case 420: return 10;break;
            default : return 1;break;
        }
    }
}
