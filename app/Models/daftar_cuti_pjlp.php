<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class daftar_cuti_pjlp extends Model
{
    use HasFactory;

    protected $table = 'daftar_cuti_pjlp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nip',
        'tgl_awal',
        'tgl_akhir',
        'total_cuti',
        'tanggal',
        'tgl_pengajuan',
        'jenis_cuti',
        'alasan',
        'alamat'
    ];

    public function data_pegawai()
    {
        return $this->belongsTo(data_pegawai::class,'nip','nip');
    }

    public function asigment_pjlp()
    {
        return $this->hasOne(asigment_pjlp::class,'no_cuti','id');
    }
}
