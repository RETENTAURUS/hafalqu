<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Bobot pengali poin: skor siswa × bobot_poin = poin yang didapat
            // Default 1.0 = tidak ada pengali (poin = skor mentah)
            $table->decimal('bobot_poin', 4, 2)->default(1.00)->after('passing_score');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('bobot_poin');
        });
    }
};
