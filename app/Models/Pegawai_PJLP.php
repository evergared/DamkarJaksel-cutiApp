<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai_PJLP extends Model
{
    use HasFactory;

    protected $table="Pegawai_PJLP";

    protected $fillable = [

        'nik',
        'no_pjlp',
        'nama',
        'golongan',
        'jabatan',
        'nip_atasan',
        'pendidikan'
    ];
}
