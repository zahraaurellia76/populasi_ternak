<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PopulasiKecamatan;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatans';
    public $timestamps = false;

    protected $fillable = [
        'kode_kecamatan',
        'nama_kecamatan',
    ];

    /**
     * Relasi ke data populasi ternak per kecamatan
     */
    public function populasiKecamatan()
    {
        return $this->hasMany(PopulasiKecamatan::class, 'kecamatan_id');
    }
}