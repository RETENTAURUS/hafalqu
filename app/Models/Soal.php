<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soals';

    protected $fillable = [
    'surat_id',
    'pertanyaan',
    'kesulitan',
    'jenis',
    'file_audio',
    'opsi_a',
    'opsi_b',
    'opsi_c',
    'opsi_d',
    'jawaban_benar'
];

    /**
     * Relasi ke model Surat (Belongs To)
     * Soal ini terikat pada satu Surat Al-Qur'an tertentu.
     */
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function quizzes()
{
    return $this->belongsToMany(Quiz::class, 'quiz_soal');
}
}