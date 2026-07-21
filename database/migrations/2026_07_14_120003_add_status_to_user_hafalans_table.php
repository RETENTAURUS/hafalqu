<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SiswaLencanaController membaca user_hafalans.status ('hafal' | 'setoran' | 'belum')
 * untuk menghitung progres badge hafalan/juz_selesai, tapi kolom ini belum ada
 * di migrasi awal user_hafalans. Migrasi ini menambahkannya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_hafalans', function (Blueprint $table) {
            $table->string('status')->default('belum')->after('surat_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_hafalans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};