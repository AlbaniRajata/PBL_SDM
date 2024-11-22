<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaModel extends Model
{
    use HasFactory;

    protected $table = 't_anggota';
    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_kegiatan',
        'id_user',
        'id_jabatan_kegiatan',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_user', 'id_user');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }

    public function poin()
    {
        return $this->hasMany(PoinModel::class, 'id_user', 'id_user');
    }
}