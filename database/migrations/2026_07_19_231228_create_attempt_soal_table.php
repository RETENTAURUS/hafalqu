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
        Schema::create('attempt_soal', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan ke id di tabel quiz_attempts yang Anda miliki
            $table->foreignId('quiz_attempt_id')
                  ->constrained('quiz_attempts')
                  ->onDelete('cascade');
                  
            // Menghubungkan ke id di tabel soals (bank soal)
            $table->foreignId('soal_id')
                  ->constrained('soals')
                  ->onDelete('cascade');
            
            // Menyimpan nomor urut tampilnya soal yang diacak
            $table->integer('order')->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_soal');
    }
};