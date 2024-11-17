<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 't_jabatan_kegiatan';
    protected $primaryKey = 'id_jabatan_kegiatan';

    protected $fillable = [
        'jabatan_nama',
        'poin',
    ];
}