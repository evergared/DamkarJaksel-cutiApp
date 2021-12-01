<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asigment_pjlp extends Model
{
    use HasFactory;

    protected $table = 'asigment_pjlp';
    protected $primaryKey = 'no_cuti';

    protected $fillable = [
        'no_cuti',
        'kasie',
        'ket_kasie',
        'ppk',
        'ket_ppk',
        'kasubagtu',
        'ket_tu',
        'selesai'
    ];

    public function daftar_cuti_pjlp()
    {
        return $this->belongsTo(daftar_cuti_pjlp::class,'no_cuti','id');
    }
}
