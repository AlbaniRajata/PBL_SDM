<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 't_user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['id_user', 'username', 'nama', 'email', 'password', 'NIP', 'level', 'poin'];

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function sdm()
    {
        return $this->hasMany(SdmModel::class, 'id_user', 'id_user');
    }
}