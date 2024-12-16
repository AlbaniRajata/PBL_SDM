<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'nama',
        'tanggal_lahir',
        'email',
        'password',
        'NIP',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getRole()
    {
        return $this->level;
    }

    public function hasRole($role): bool
    {
        return $this->level == $role;
    }

    public function kegiatan()
    {
        return $this->hasMany(PoinModel::class, 'id_user', 'id_user');
    }

    public function jabatan()
    {
        return $this->hasMany(PoinModel::class, 'id_user', 'id_user');
    }

    public function poin()
    {
        return $this->hasMany(PoinModel::class, 'id_user', 'id_user');
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaModel::class, 'id_user', 'id_user');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenModel::class, 'id_user', 'id_user');
    }

    public function jabatan_kegiatan()
    {
        return $this->hasMany(JabatanKegiatanModel::class, 'id_user', 'id_user');
    }

}