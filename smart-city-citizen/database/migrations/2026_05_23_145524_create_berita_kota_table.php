<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_kota', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('isi');
            $table->string('kategori', 100);
            $table->string('penulis', 150);
            $table->date('tanggal_terbit');
            $table->timestamps();

            $table->index('judul');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_kota');
    }
};