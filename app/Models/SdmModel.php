<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SdmModel extends Model
{
    use HasFactory;

    protected $table = 't_sdm';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'id_user', 'id_kegiatan', 'id_dokumen'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_user', 'id_user');
    }

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }
}