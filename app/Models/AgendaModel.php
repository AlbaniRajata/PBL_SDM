<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaModel extends Model
{
    use HasFactory;

    protected $table = 't_agenda';
    protected $primaryKey = 'id_agenda';

    protected $fillable = [
        'id_kegiatan',
        'id_dokumen',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function dokumen()
    {
        return $this->belongsTo(DokumenModel::class, 'id_dokumen', 'id_dokumen');
    }
}