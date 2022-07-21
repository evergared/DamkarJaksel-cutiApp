<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

use App\Models\Pengumuman;

Class PengumumanController extends Controller{

    public function addPengumuman(Request $r)
    {
        try{

            $post = new Pengumuman;

            $post->judul = $r->input('judul');
            $post->nip_penulis = Auth::user()->nip;
            $post->nama_penulis = Auth::user()->data['nama'];
            $post->slug = Str::slug($r->input('judul'));
            $post->isi = $r->input('isi');

            $post->save();

            return redirect()->back()->with('addSuccess','Pengumuman berhasil dibuat!');
        }
        catch(Throwable $e)
        {
            error_log('Failure on create new pengumuman post error : '.$e);
            report('Failure on create new pengumuman post error : '.$e);
            return redirect()->back()->with('addFail','Pengumuman gagal dibuat!');
        }
    }

}

?>