<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id', 'soal_id', 'selected_answer', 'is_correct'
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}