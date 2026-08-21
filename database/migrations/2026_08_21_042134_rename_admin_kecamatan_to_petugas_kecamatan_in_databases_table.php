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
        Schema::table('users', function (Blueprint $table) {
            // Mengubah definisi pilihan ENUM pada kolom 'role'
            $table->enum('role', ['admin_kabupaten', 'petugas_kecamatan'])
                  ->default('petugas_kecamatan')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan ke pilihan ENUM awal
            $table->enum('role', ['admin_kabupaten', 'admin_kecamatan'])
                  ->default('admin_kecamatan')
                  ->change();
        });
    }
};