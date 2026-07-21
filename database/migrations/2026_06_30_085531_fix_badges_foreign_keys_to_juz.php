<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah foreign key sudah ada
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'badges' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        $foreignKeyNames = array_column($foreignKeys, 'CONSTRAINT_NAME');

        Schema::table('badges', function (Blueprint $table) use ($foreignKeyNames) {
            // Hapus foreign key jika ada
            if (in_array('badges_juz_id_foreign', $foreignKeyNames)) {
                $table->dropForeign('badges_juz_id_foreign');
            }
        });

        // Tambahkan foreign key baru (dengan pengecekan tabel juz)
        Schema::table('badges', function (Blueprint $table) {
            // Pastikan kolom juz_id ada
            if (!Schema::hasColumn('badges', 'juz_id')) {
                $table->unsignedBigInteger('juz_id')->nullable();
            }

            // Tambahkan foreign key ke tabel juz
            $table->foreign('juz_id')
                  ->references('id')
                  ->on('juz')   // ← nama tabel yang benar
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            try {
                $table->dropForeign(['juz_id']);
            } catch (\Exception $e) {
                // Ignore jika foreign key tidak ada
            }
        });
    }
};