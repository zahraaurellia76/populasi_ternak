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
            // Menghapus kolom status_validasi
            $table->dropColumn('status_validasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('populasi_kecamatans', function (Blueprint $table) {
            // Mengembalikan kolom jika dilakukan rollback
            $table->enum('status_validasi', ['draft', 'disetujui'])->default('draft');
        });
    }
};