<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenModel extends Model
{
    use HasFactory;

    protected $table = 't_dokumen';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_kegiatan',
        'nama_dokumen',
        'file_path',
        'progress',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }
}