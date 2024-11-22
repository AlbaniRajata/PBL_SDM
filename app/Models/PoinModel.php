<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinModel extends Model
{
    protected $table = 't_poin';
    protected $primaryKey = 'id_poin';

    protected $fillable = [
        'id_kegiatan',
        'id_user',
        'poin',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_user', 'id_user');
    }
}
