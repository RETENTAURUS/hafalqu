<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'kelas_id',
        'points'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];



    // app/Models/User.php
public function badges()
{
    return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at')->withTimestamps();
}

public function kelas()
{
    return $this->belongsTo(Kelas::class);
}

public function quizAttempts()
{
    return $this->hasMany(QuizAttempt::class);
}

public function hafalans()
{
    return $this->hasMany(UserHafalan::class);
}
public function getLevelAttribute()
{
    // Ambil dari tabel levels berdasarkan points
    return Level::where('min_points', '<=', $this->points)
                ->where(function($query) {
                    $query->whereNull('max_points')->orWhere('max_points', '>=', $this->points);
                })
                ->first()->name ?? 'Pemula';
}

public function getProgressToNextLevelAttribute()
{
    $current = Level::where('min_points', '<=', $this->points)
                    ->where(function($query) {
                        $query->whereNull('max_points')->orWhere('max_points', '>=', $this->points);
                    })->first();

    if (!$current) return 0;
    if ($current->max_points === null) return 100; // sudah di level tertinggi

    $next = Level::where('min_points', '>', $current->min_points)->orderBy('min_points')->first();
    if (!$next) return 100;

    $range = $next->min_points - $current->min_points;
    $progress = $this->points - $current->min_points;
    return round(($progress / $range) * 100);
}
}