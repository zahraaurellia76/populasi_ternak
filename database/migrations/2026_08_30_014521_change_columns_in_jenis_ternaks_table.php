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
        Schema::table('jenis_ternaks', function (Blueprint $table) {
            // Mengubah panjang kolom nama_ternak menjadi 25
            $table->string('nama_ternak', 25)->change();
            
            // Mengubah panjang kolom kategori menjadi 12 (jika sebelumnya bertipe string/varchar)
            $table->string('kategori', 12)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_ternaks', function (Blueprint $table) {
            // Kembalikan ke panjang semula jika rollback
            $table->string('nama_ternak', 100)->change();
            $table->string('kategori', 255)->change();
        });
    }
};