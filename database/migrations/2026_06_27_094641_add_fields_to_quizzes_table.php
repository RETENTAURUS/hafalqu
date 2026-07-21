<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Kelas tujuan (opsional, nullable untuk semua kelas)
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete()->after('title');
            
            // Durasi dalam menit
            $table->integer('duration')->default(30)->after('is_active'); // dalam menit
            
            // Tanggal mulai dan selesai (opsional)
            $table->timestamp('start_date')->nullable()->after('duration');
            $table->timestamp('end_date')->nullable()->after('start_date');
            
            // Batas percobaan (0 untuk tak terbatas)
            $table->integer('attempt_limit')->default(0)->after('end_date'); // 0 = unlimited
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['kelas_id', 'duration', 'start_date', 'end_date', 'attempt_limit']);
        });
    }
};