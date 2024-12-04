<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaAnggotaModel extends Model
{
    use HasFactory;

    protected $table = 't_agenda_anggota';
    protected $primaryKey = 'id_agenda_anggota';

    protected $fillable = [
        'id_agenda',
        'id_anggota',
        'nama_agenda',
    ];

    public function agenda()
    {
        return $this->hasMany(AgendaModel::class, 'id_dokumen', 'id_dokumen');
    }

    public function anggota()
    {
        return $this->belongsTo(AnggotaModel::class, 'id_anggota', 'id_anggota');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_user', 'id_user');
    }
}