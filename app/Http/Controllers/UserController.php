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
}
