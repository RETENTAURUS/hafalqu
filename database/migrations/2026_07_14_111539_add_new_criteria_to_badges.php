<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Extends the badges.criteria_type ENUM to support:
 *  - login_streak      -> criteria_value = number of consecutive login days
 *  - leaderboard_rank  -> criteria_value = required leaderboard rank (1 = top rank)
 *
 * The original enum only covered: poin, quiz_selesai, nilai_sempurna, hafalan, juz_selesai
 * which is not enough to represent "Raja Quiz" (leaderboard rank) or the
 * login-streak badges (Semangat 3 Hari, Konsisten 7 Hari, Istiqamah 14 Hari,
 * Bintang Istiqamah).
 */
return new class extends Migration
{
    public function up(): void
    {
        // MySQL/MariaDB requires redefining the ENUM to add values.
        DB::statement("ALTER TABLE badges MODIFY COLUMN criteria_type ENUM(
            'poin',
            'quiz_selesai',
            'nilai_sempurna',
            'hafalan',
            'juz_selesai',
            'login_streak',
            'leaderboard_rank'
        ) NOT NULL");
    }

    public function down(): void
    {
        // Revert to the original set. Any rows using the new types must be
        // migrated/deleted first or this will fail on strict SQL modes.
        DB::statement("ALTER TABLE badges MODIFY COLUMN criteria_type ENUM(
            'poin',
            'quiz_selesai',
            'nilai_sempurna',
            'hafalan',
            'juz_selesai'
        ) NOT NULL");
    }
};