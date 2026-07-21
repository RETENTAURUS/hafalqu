<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // misal "AI - Hafidz | Level 4"
            $table->integer('min_points')->default(0);
            $table->integer('max_points')->nullable(); // null = unlimited
            $table->timestamps();
        });

        // Seed default levels
        DB::table('levels')->insert([
            ['name' => 'Pemula', 'min_points' => 0, 'max_points' => 100],
            ['name' => 'AI - Hafidz | Level 1', 'min_points' => 100, 'max_points' => 300],
            ['name' => 'AI - Hafidz | Level 2', 'min_points' => 300, 'max_points' => 500],
            ['name' => 'AI - Hafidz | Level 3', 'min_points' => 500, 'max_points' => 700],
            ['name' => 'AI - Hafidz | Level 4', 'min_points' => 700, 'max_points' => 1000],
            ['name' => 'AI - Hafidz | Level 5', 'min_points' => 1000, 'max_points' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};