<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;
use App\Models\User;
use Carbon\Carbon;

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
            $nip = $request->input('nip');
            $password = $request->input('password');
            $email = $request->input('email');
            $bukanPegawai = $request->input('bukanPegawai');
            $nama = $request->input('nama');
            $peran = $request->input('peran');
            $penempatan = $request->input('nip');
            $userExist = false;

            if(sizeof($peran) == 0)
                $peran = $this->getRoles($nip);
            else
                $peran = implode('|',$peran);

            if(!DB::table('user')->where('nip','=',$nip)->exists())
            DB::table('user')->insert([
                'nip' => $nip,
                'level' => $peran,
                'password' => Hash::make($password),
                'email' => $email,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            else
                $userExist = true;

            if(!$userExist)
            {
                if($bukanPegawai)
                {
                    error_log('peran : '.$peran);
                    if(is_string($peran))
                        $peran = explode('|',$peran);

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
                        else
                            $Jabatan = 22;
                    }
                    if(!DB::table('data_pegawai')->where('nip','=',$nip)->exists())
                    DB::table('data_pegawai')->insert([
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

            return 'fail_add_user_exist';

            
        }
        catch(Throwable $e){
            report('Add user error : '.$e);
            error_log('Add user error : '.$e);
            return 'fail_add_user_try_caught';
        }
    }

    public function deleteUser(Request $request)
    {
        try{

            $nip = $request->input('nip');

            if(DB::table('user')->where('nip','=',$nip)->exists())
            {
                $user = DB::table('user')->where('nip','=',$nip);
                $roles = explode('|',$user->value('level'));
                $sementara = in_array('SEMENTARA',$roles);

                $user->delete();

                if($sementara)
                    if(DB::table('data_pegawai')->where('nip','=',$nip)->exists())
                        DB::table('data_pegawai')->where('nip','=',$nip)->delete();

                return "success_delete_user";
            }
            else
                return "fail_delete_user_exist";
        }
        catch(Throwable $e){
            report('Delete user error : '.$e);
            error_log('Delete user error : '.$e);
            return 'fail_delete_user_try_caught';
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

    public function changeLevel(Request $request)
    {
        try{
            $nip = $request->input('nip');
            $level = $request->input('peran');
            //$penempatan = $request->input('penempatan');
            $user = DB::table('user')->where('nip','=',$nip);

            if($user->exists())
            {
                $user->update([
                    'level' => implode('|',$level)
                ]);
            }
            else
                return 'fail_change_level_not_exist';
            
            return 'success_change_level';
        }
        catch(Throwable $e){
            error_log('Level Change Error on Nip '.$nip.'! error : '.$e);
            report('Level Change Error on Nip '.$nip.'! error : '.$e);
            return 'fail_change_level_try_caught';
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

    function getRoles($nip)
    {
        $pjlp = [16,17,18,19];
        $kasie = [1,2,3,4,5,6,7,8,9,10,12];
        $person = DB::table('data_pegawai')->where('nip',$nip)->first();

        if(in_array($person->jabatan,$pjlp))
            $this->addRoles($roles,"PJLP");
        else
            $this->addRoles($roles,"ASN");

        $ket = explode('|',$person->keterangan);
        if(in_array("Admin",$ket))
        $this->addRoles($roles,"ADMIN");

        if((25 <= $person->jabatan) && ($person->jabatan <= 54))
            $this->addRoles($roles,"KATON");
        elseif($person->jabatan === 21)
            $this->addRoles($roles,"KARU");

        if(in_array($person->jabatan,$kasie))
            $this->addRoles($roles,"KASIE");
        elseif($person->jabatan == 11)
            $this->addRoles($roles, "KASUBAGTU");
        elseif($person->jabatan == 15)
            $this->addRoles($roles,"KASUDIN");

        if($person->nip === DB::table('jabatan')->where('no',14)->first()->keterangan)
            $this->addRoles($roles,"PPK");

        if($person->nip === DB::table('jabatan')->where('no',23)->first()->keterangan)
            $this->addRoles($roles,"KASIE.PENCEGAHAN");

        return $roles;
    }

    function addRoles(&$roles,$role)
    {
        if(is_null($roles))
            $roles = $role;
        else
            $roles = $roles . "|" . $role;
    }
}
