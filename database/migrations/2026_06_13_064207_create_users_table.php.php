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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            
            // FIELD POIN (tanpa after)
            $table->integer('points')->default(0);
            
            $table->string('password');
            $table->enum('role', ['siswa', 'guru', 'admin'])->default('siswa');

            $table->foreignId('kelas_id')
                  ->nullable()
                  ->constrained('kelas')
                  ->nullOnDelete();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};