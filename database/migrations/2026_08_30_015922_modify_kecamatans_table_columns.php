<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            // Mengubah panjang kode_kecamatan menjadi 3 digit (misal: "141")
            $table->string('kode_kecamatan', 3)->change();
            
            // Mengubah panjang nama_kecamatan menjadi maksimal 20 karakter
            $table->string('nama_kecamatan', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            // Kembalikan ke panjang semula saat rollback (sesuaikan jika sebelumnya berbeda)
            $table->string('kode_kecamatan', 10)->change();
            $table->string('nama_kecamatan', 50)->change();
        });
    }
};