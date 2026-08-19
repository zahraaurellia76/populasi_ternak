<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTernak extends Model
{
    use HasFactory;

    protected $table = 'jenis_ternaks';
    public $timestamps = false;

    protected $fillable = [
        'nama_ternak',
        'kategori',
    ];
}


