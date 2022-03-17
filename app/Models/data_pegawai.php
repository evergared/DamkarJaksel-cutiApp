<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_pegawai extends Model
{
    use HasFactory;

    protected $table = "data_pegawai";

    protected $fillable = [
        'nip',
        'nrk',
        'nama',
        'golongan',
        'jabatan',
        'kasie',
        'atasan',
        'pendidikan',
        'kode_penempatan',
        'kompi',
        'keterangan',
        'jabket',
        'masker'
    ];

    public function daftar_cuti_asn()
    {
        return $this->hasMany(daftar_cuti_asn::class,'nip','nip');
    }

    public function daftar_cuti_pjlp()
    {
        return $this->hasMany(daftar_cuti_pjlp::class,'nip','nip');
    }

    public function asigment_asn()
    {
        return $this->hasManyThrough(
            asigment_asn::class,
            daftar_cuti_asn::class
            );
    }
    
    public function asigment_pjlp()
    {
        return $this->hasManyThrough(asigment_pjlp::class,daftar_cuti_pjlp::class);
    }
}
