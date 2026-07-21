<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHafalan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'surat_id',
        'status',
        'completed_at',
    ];

    protected $attributes = [
        'status' => 'belum',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}