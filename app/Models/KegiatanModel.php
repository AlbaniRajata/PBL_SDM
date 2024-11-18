<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KegiatanModel extends Model
{
    use HasFactory;

    protected $table = 't_kegiatan';
    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_acara',
        'tempat_kegiatan',
        'jenis_kegiatan',
        'progress',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_acara' => 'date',
    ];

    // Accessors to format the dates
    public function getTanggalMulaiAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTanggalSelesaiAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTanggalAcaraAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}