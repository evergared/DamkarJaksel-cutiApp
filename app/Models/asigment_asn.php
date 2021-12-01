<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asigment_asn extends Model
{
    use HasFactory;

    protected $table = 'asigment_asn';
    protected $primaryKey = 'no_cuti';

    protected $fillable = [
        'no_cuti',
        'kasie',
        'ket_kasie',
        'kasubagtu',
        'ket_tu',
        'selesai'
    ];

    public function daftar_cuti_asn()
    {
        return $this->belongsTo(daftar_cuti_asn::class,'no_cuti','id');
    }
}
