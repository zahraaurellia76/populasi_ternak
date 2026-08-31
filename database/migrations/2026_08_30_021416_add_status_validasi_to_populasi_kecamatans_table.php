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
        Schema::table('populasi_kecamatans', function (Blueprint $table) {
            // Menambahkan kembali kolom status_validasi
            $table->enum('status_validasi', ['draft', 'disetujui'])->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('populasi_kecamatans', function (Blueprint $table) {
            // Menghapus kolom status_validasi jika rollback
            $table->dropColumn('status_validasi');
        });
    }
};