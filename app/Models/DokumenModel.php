<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenModel extends Model
{
    use HasFactory;

    protected $table = 'm_dokumen';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_kegiatan',
        'nama_dokumen',
        'jenis_dokumen',
        'file_path',
        'progress',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function agenda()
    {
        return $this->hasMany(AgendaModel::class, 'id_dokumen', 'id_dokumen');
    }
}