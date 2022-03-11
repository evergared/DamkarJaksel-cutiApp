<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Throwable;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register (Request $request)
    {
        try{
            error_log('first');
            $nip = DB::table('data_pegawai')->pluck('nip')->toArray();
            $nrk = DB::table('data_pegawai')->pluck('nrk')->toArray();
            error_log('second');
            $us = new UserController();
            $check = false;
            
            if(in_array($request['nip-nrk'],$nrk)) $check = true;
            
            if(!$check)
            if(in_array($request['nip-nrk'],$nip)) $check = true;
            error_log('third');
            if(!$check)
            {
                $request->session()->flash('registerError','NIP / NRK / No PJLP tidak ditemukan! Harap hubungi admin.');
                return redirect('register')->withInput($request->except('password'));
            }
            
            $nip = DB::table('user')->pluck('nip')->toArray();
            error_log('fourth');
            $validated = $request->validate([
                'nip-nrk' => ['required','string','max:18', Rule::notIn($nip)],
                //'email' => 'email:dns|max:255|unique:user',
                'password' => 'required|string|confirmed'
            ]);
            // ambil data berdasarkan nip / nrk yang didapat
            if(in_array($request['nip-nrk'],$nrk))
                $person = DB::table('data_pegawai')->where('nrk',$request['nip-nrk'])->first();
            else
                $person = DB::table('data_pegawai')->where('nip',$request['nip-nrk'])->first();
                error_log('fifth');
    
            // 1. get person data dari data pegawai
            // 2. buat array untuk menyimpan role
            // 3. loop pemanggilan data dari nip
    
            if(User::create([
                'nip' => $person->nip,
                'level' => $us->getRoles($person->nip),
                'password' => Hash::make($request['password']),
                'email' => $request['email']
            ]));
            error_log('sixth');
            //dd('registrasi berhasil untuk pengguna dengan nip : ' . $person->nip);
            //implode(" | ",$person);
            return redirect("login")->with('registerSuccess','Registrasi User Berhasil!');
        }
        catch (Throwable $e)
        {
            $timestamp = now();
            error_log('register fail on data : '.$request['nip-nrk'].' with error : '.$e);
            report('register fail on data : '.$request['nip-nrk'].' with error : '.$e);
            return redirect('register')->with('registerError','Gagal registrasi! Harap hubungi admin. ('.$timestamp.')');
        }
        
        
    }

    protected function registered(Request $request, $user)
    {
        error_log("The event should be triggered by now");
        if(event(new Registered($user)))
            error_log("event triggered and it's should be true");
        return redirect("login");
        //route('verification.notice');
    }


}
