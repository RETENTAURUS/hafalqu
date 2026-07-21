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
        Schema::table('soals', function (Blueprint $table) {
            // Menambahkan kolom kesulitan setelah kolom jawaban_benar
            $table->enum('kesulitan', ['Mudah', 'Sedang', 'Sulit'])
                  ->default('Mudah')
                  ->after('jawaban_benar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            // Menghapus kolom kesulitan jika migrasi di-rollback
            $table->dropColumn('kesulitan');
        });
    }
};