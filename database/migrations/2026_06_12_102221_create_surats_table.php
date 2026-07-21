<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel juz. Jika juz dihapus, data surat di dalamnya ikut terhapus.
            $table->foreignId('juz_id')->constrained('juz')->onDelete('cascade'); 
            $table->integer('nomor_surat'); // Contoh: 78
            $table->string('nama_surat');   // Contoh: An-Naba
            $table->integer('total_ayat');  // Contoh: 40
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};