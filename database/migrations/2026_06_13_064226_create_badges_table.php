<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();

            // Kriteria
            $table->enum('criteria_type', [
                'poin', 'quiz_selesai', 'nilai_sempurna', 'hafalan', 'juz_selesai'
            ])->default('poin');
            $table->integer('criteria_value')->default(0);

            // Foreign keys (gunakan nama tabel yang benar)
            $table->foreignId('quiz_id')->nullable()->constrained('quizzes')->onDelete('set null');
            $table->foreignId('surat_id')->nullable()->constrained('surats')->onDelete('set null');
            $table->foreignId('juz_id')->nullable()->constrained('juz')->onDelete('set null'); // ← `juz` (tanpa 's')

            // Status & level
            $table->boolean('is_active')->default(true);
            $table->enum('level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->integer('required_points')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};