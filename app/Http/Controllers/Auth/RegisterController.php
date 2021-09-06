<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataPegawaiController;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class RegisterController extends Controller
{
    

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register (Request $request)
    {
        $nip = DB::table('data_pegawai')->pluck('nip')->toArray();
        $nrk = DB::table('data_pegawai')->pluck('nrk')->toArray();
        $check = false;
        
        if(in_array($request['nip-nrk'],$nrk)) $check = true;
        
        if(!$check)
        if(in_array($request['nip-nrk'],$nip)) $check = true;

        if(!$check)
        {
            $request->session()->flash('registerErrorNip','NIP / NRK / No PJLP tidak ditemukan! Harap hubungi admin.');
            return redirect('register')->withInput($request->except('password'));
        }
        
        $nip = DB::table('user')->pluck('nip')->toArray();

        $validated = $request->validate([
            'nip-nrk' => ['required','string','max:18', Rule::notIn($nip)],
            'email' => 'required|email:dns|max:255|unique:user',
            'password' => 'required|string|confirmed'
        ]);

        dd('registrasi berhasil');

        // TODO : Logic create new user
        //User::create([]);

    }
}
