<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['nama', 'username', 'password', 'role', 'kecamatan_id'];
    protected $hidden = ['password'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function populasi()
    {
        return $this->hasMany(PopulasiKecamatan::class);
    }
}