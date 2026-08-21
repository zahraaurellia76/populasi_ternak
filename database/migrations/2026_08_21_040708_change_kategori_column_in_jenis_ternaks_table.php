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
            // Mengubah kolom 'kategori' dari ENUM menjadi VARCHAR (string)
            $table->string('kategori')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_ternaks', function (Blueprint $table) {
            $table->enum('kategori', ['Ruminansia Besar', 'Ruminansia Kecil', 'Unggas', 'Lainnya'])->change();
        });
    }
};