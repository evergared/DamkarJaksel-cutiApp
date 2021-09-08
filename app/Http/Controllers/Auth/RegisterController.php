<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\DataPegawai as pegawai;
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
        
        //$person = "no";

        if(in_array($request['nip-nrk'],$nrk))
            //$person = "nrk";
            $person = DB::table('data_pegawai')->where('nrk',$request['nip-nrk'])->get()->toArray();
        else
            //$person = "nip";
        $person = DB::table('data_pegawai')->where('nip',$request['nip-nrk'])->get()->toArray();

        // TODO : buat logic untuk insert level user berdasarkan hierarki dan jabatan yang dipegang
        // ide : gunakan composite value dengan separator, cth kasek|ppk

        // if(User::create([
        //     'nip' => $person['nip'],
        //     'level' => '-',
        //     'password' => Hash::make($request['password']),
        //     'email' => $request['email']
        // ]))

        dd('registrasi berhasil untuk pengguna dengan nip : ' . count($person));
        //implode(" | ",$person);
        
    }
}
