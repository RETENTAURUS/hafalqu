<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->text('pertanyaan');

            // Jenis soal: melanjutkan, mengisi, pengetahuan, audio
            $table->enum('jenis', ['melanjutkan', 'mengisi', 'pengetahuan', 'audio'])->default('pengetahuan');

            $table->string('file_audio')->nullable(); // hanya untuk audio

            // Pilihan ganda A–D
            $table->string('opsi_a');
            $table->string('opsi_b');
            $table->string('opsi_c');
            $table->string('opsi_d');
            $table->char('jawaban_benar', 1); // A, B, C, D

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};