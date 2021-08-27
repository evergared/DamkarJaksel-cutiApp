<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai_ASN extends Model
{
    use HasFactory;

    protected $table="Pegawai_ASN";

    protected $fillable = [

        'nik',
        'nrk',
        'nama',
        'golongan',
        'jabatan',
        'nip_atasan',
        'pendidikan'
    ];
}
