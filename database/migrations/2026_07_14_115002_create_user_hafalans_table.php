<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel ini mencatat progres hafalan siswa per-surat.
 * Satu baris = satu surat yang sudah diselesaikan/dihafal oleh user.
 *
 * ASUMSI: tabel `surats` sudah punya kolom `juz_id` yang menunjukkan
 * surat tersebut termasuk juz berapa. Kalau strukturmu berbeda
 * (misal relasi many-to-many surat<->juz, atau progres per-ayat bukan
 * per-surat), beri tahu saya supaya migrasi & model ini disesuaikan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_hafalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'surat_id']); // satu surat hanya dihitung sekali per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_hafalans');
    }
};