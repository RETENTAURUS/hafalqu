<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom untuk melacak login streak (dipakai oleh badge
 * "Semangat 3 Hari", "Konsisten 7 Hari", dst).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('login_streak')->default(0)->after('remember_token');
            $table->date('last_login_date')->nullable()->after('login_streak');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_streak', 'last_login_date']);
        });
      }
};