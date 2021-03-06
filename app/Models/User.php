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
        'is_plt',
        'is_pjlp',
        'is_asn',
        'is_admin',
        'roles',
        'jabatan',
        'data',
        'has_subordinate',
        'has_subordinate_pjlp',
        'can_request_cuti',
        'is_kasie',
        'is_kasubag_tu',
        'is_ppk',
        'is_kasudin',
        'is_approver'
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
        'jabatan' => 'string',
        'data' => 'array',
        'is_plt' => 'boolean',
        'is_admin' =>'boolean',
        'is_pjlp' => 'boolean',
        'is_asn' => 'boolean',
        'is_kasie' => 'boolean',
        'is_kasubag_tu' => 'boolean',
        'is_ppk' => 'boolean',
        'is_kasudin' => 'boolean',
        'has_subordinate' => 'boolean',
        'has_subordinate_pjlp' => 'boolean',
        'can_request_cuti' => 'boolean',
        'is_approver' => 'boolean'
    ];

    public function getDataAttribute()
    {
        return (array) DB::table('data_pegawai')->where('nip',$this->nip)->first();
    }

    public function getJabatanAttribute()
    {
        return data_pegawai::where('nip',$this->nip)->value('jabatan');
    }

    public function getAtasanAttribute()
    {
        return data_pegawai::where('nip',$this->nip)->value('atasan');
    }

    public function getKasieAttribute()
    {
        return data_pegawai::where('nip',$this->nip)->value('kasie');
    }

    public function getRolesAttribute()
    {
        return explode('|',$this->level);
    }

    public function getIsAdminAttribute()
    {
        return in_array('ADMIN',$this->roles);
    }

    public function getIsPjlpAttribute()
    {
        return data_pegawai::where('nip',$this->nip)->value('golongan') === 'PJLP';
    }

    public function getIsAsnAttribute()
    {
        return in_array('ASN',$this->roles);
    }

    public function getIsKasieAttribute()
    {
        return (in_array('KASIE',$this->roles) || in_array('KASIE.PENCEGAHAN',$this->roles));
    }

    public function getIsKasubagTuAttribute()
    {
        return in_array('KASUBAGTU',$this->roles);
    }

    public function getIsPpkAttribute()
    {
        return in_array('PPK',$this->roles);
    }

    public function getIsKasudinAttribute()
    {
        return in_array('KASUDIN',$this->roles);
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

    public function getCanRequestCutiAttribute()
    {
        if($this->is_pjlp)
            return true;
        else
        {
            if($this->is_kasie || $this->is_kasubag_tu || $this->is_ppk || $this->is_kasudin)
                return false;
            return true;
        }
    }

    public function getIsApproverAttribute()
    {
        return ($this->is_kasie || $this->is_kasubag_tu || $this->is_ppk);
    }

    public function getJabatanPltAttribute()
    {
        if(!$this->is_plt)
        return "fail_plt_user_is_not_plt";

        else
        {
            if(DB::table('plt')->where('nip_pelaksana','=',$this->nip)->exists())
            {
                $test =  DB::table('plt')->where('nip_pelaksana','=',$this->nip)->pluck('kode_jabatan');
                error_log('user model get jabatan plt : '.$test);
                return $test;
            }
            else
                return "fail_plt_user_data_not_found";
        }
    }
}
