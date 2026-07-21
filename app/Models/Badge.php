<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'image',
        'criteria_type',
        'criteria_value',
        'quiz_id',
        'surat_id',
        'juz_id',
        'is_active',
        'level',
        'required_points',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'criteria_value' => 'integer',
        'required_points' => 'integer',
    ];

    // Relasi
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function juz()
    {
        return $this->belongsTo(Juz::class); // <- merujuk ke tabel 'juz'
    }
public function users()
{
    return $this->belongsToMany(User::class, 'user_badges')->withPivot('earned_at')->withTimestamps();
}

    // Accessor
    public function getCriteriaLabelAttribute()
    {
        $labels = [
            'poin' => 'Poin',
            'quiz_selesai' => 'Jumlah Quiz Selesai',
            'nilai_sempurna' => 'Nilai Sempurna (100)',
            'hafalan' => 'Jumlah Hafalan',
            'juz_selesai' => 'Juz Selesai',
        ];
        return $labels[$this->criteria_type] ?? $this->criteria_type;
    }

    public function getLevelBadgeAttribute()
    {
        $levels = [
            'bronze' => ['label' => 'Perunggu', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
            'silver' => ['label' => 'Perak', 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
            'gold' => ['label' => 'Emas', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
            'platinum' => ['label' => 'Platinum', 'bg' => 'bg-slate-100', 'text' => 'text-slate-600'],
        ];
        return $levels[$this->level] ?? $levels['bronze'];
    }
}