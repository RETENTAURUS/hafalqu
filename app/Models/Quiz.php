<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
protected $fillable = [
    'title',
    'kelas_id',
    'tipe_pengerjaan',
    'juz_id',
    'is_active',
    'duration',
    'start_date',
    'end_date',
    'attempt_limit',
    'order',    // tambahkan
    'config',   // tambahkan
];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function soals()
    {
        return $this->belongsToMany(Soal::class, 'quiz_soal')->withPivot('order')->withTimestamps();
    }

       public function juz()
   {
       return $this->belongsTo(Juz::class);
   }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // app/Models/Quiz.php


public function userAttempts($userId)
{
    return $this->attempts()->where('user_id', $userId);
}

public function isCompletedByUser($userId)
{
    return $this->userAttempts($userId)->where('score', '>=', $this->passing_score)->exists();
}

public function isPerfectByUser($userId)
{
    return $this->userAttempts($userId)->where('score', 100)->exists();
}

public function getRemainingAttempts($userId)
{
    if ($this->max_attempts === null) return PHP_INT_MAX;
    $used = $this->userAttempts($userId)->count();
    return max(0, $this->max_attempts - $used);
}
}