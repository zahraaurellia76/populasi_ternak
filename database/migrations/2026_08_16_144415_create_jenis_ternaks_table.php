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
        Schema::create('jenis_ternaks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ternak', 100);
            $table->enum('kategori', ['Ruminansia Besar', 'Ruminansia Kecil', 'Unggas', 'Lainnya']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_ternaks');
    }
};
