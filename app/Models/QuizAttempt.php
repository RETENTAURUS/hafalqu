<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id', 'quiz_id', 'score', 'total_questions',
        'correct_answers', 'started_at', 'finished_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function siswa()
{
    return $this->hasMany(User::class, 'kelas_id')->where('role', 'siswa');
}

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function getProgressAttribute()
    {
        return $this->answers()->count();
    }

    public function getPercentageAttribute()
    {
        if ($this->total_questions == 0) return 0;
        return round(($this->correct_answers / $this->total_questions) * 100);
    }
    public function soals() {
    return $this->belongsToMany(Soal::class, 'attempt_soal', 'quiz_attempt_id', 'soal_id')->withPivot('order');
}
}