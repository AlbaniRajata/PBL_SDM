<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileModel extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'files';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_file',
        'kategori',
        'ukuran_file',
        'tipe_file',
        'file_path',
        'created_at',
        'updated_at',
    ];

    // Relasi ke model Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'kegiatan_id', 'id_kegiatan');
    }
}