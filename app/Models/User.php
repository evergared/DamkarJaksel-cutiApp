<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nip',
        'level',
        'password',
        'email',
        'email_verified_at',
        'is_admin',
        'roles',
        'data',
        'has_subordinate',
        'has_subordinate_pjlp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
        'data' => 'array',
        'is_admin' =>'boolean',
        'has_subordinate' => 'boolean',
        'has_subordinate_pjlp' => 'boolean'
    ];

    public function getDataAttribute()
    {
        return (array) DB::table('data_pegawai')->where('nip',$this->nip)->first();
    }

    public function getRolesAttribute()
    {
        return explode('|',$this->level);
    }

    public function getIsAdminAttribute()
    {
        return in_array('ADMIN',$this->roles);
    }

    public function getHasSubordinateAttribute()
    {
        // TODO : pindahkan fungsi attribute ke constructor untuk memperingan kerja db
        return DB::table('data_pegawai')->where('atasan',$this->data['jabatan'])->exists();
    }

    public function getHasSubordinatePjlpAttribute()
    {
        return DB::table('data_pegawai')->where('atasan',$this->data['jabatan'])->where('golongan','PJLP')->exists();
    }
}
