<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sebelumnya, hubungan "quiz ini tentang juz berapa" hanya ada di kepala guru
 * (dipilih manual lewat dropdown "Quiz Spesifik" di form badge). Migrasi ini
 * membuat hubungan itu eksplisit di database, sehingga:
 *  - Sistem bisa otomatis menemukan quiz yang cocok untuk sebuah juz
 *  - Guru tidak perlu lagi mengingat/mengikat quiz_id manual per badge juz
 *  - Terbuka kemungkinan satu juz diuji lebih dari satu quiz di masa depan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('juz_id')->nullable()->after('id')->constrained('juz')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('juz_id');
        });
    }
};