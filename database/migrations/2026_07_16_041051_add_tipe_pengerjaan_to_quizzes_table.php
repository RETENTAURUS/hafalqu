<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom untuk membedakan quiz yang dikerjakan di rumah vs di sekolah.
 * Nilai default 'sekolah' dipilih supaya quiz-quiz yang sudah ada sebelumnya
 * (dibuat sebelum fitur ini ada) tetap diperlakukan seperti biasa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('tipe_pengerjaan', ['rumah', 'sekolah'])

                ->after('juz_id');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('tipe_pengerjaan');
        });
    }
};