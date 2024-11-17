<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 't_user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'nama',
        'tanggal_lahir',
        'email',
        'password',
        'NIP',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}