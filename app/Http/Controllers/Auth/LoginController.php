<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController as dashboard;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'nip';
    }

    protected function attemptLogin(Request $request)
    {
        // jika user login dengan nip
        $pegawai = DB::table('user')->pluck('nip')->toArray();

        if(in_array($request->get('nip'),$pegawai))
            return $this->guard()->attempt(
                $this->credentials($request), $request->filled('remember')
            );

        // jika user login dengan nrk
        elseif($pegawai = DB::table('data_pegawai')->where('nrk',$request->get('nip'))->first())
        {
            $cred = [$this->username() => $pegawai->nip, "password" => $request->get('password')];
            return $this->guard()->attempt(
                $cred, $request->filled('remember'));
        }
    }

    protected function authenticated(Request $request, $user)
    {
        dd("masuk dengan cred : " . $user);
        //dashboard::getDashboard($request);
    }
}
