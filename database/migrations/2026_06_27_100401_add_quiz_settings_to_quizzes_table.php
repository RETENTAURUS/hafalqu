<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {    
            $table->integer('passing_score')->default(100); // nilai minimal untuk dianggap lulus
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['duration', 'order', 'max_attempts', 'start_date', 'end_date', 'passing_score']);
        });
    }
};