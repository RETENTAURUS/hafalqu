<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function guru()
{
    return $this->belongsTo(User::class, 'guru_id');
}

public function siswa()
{
    return $this->hasMany(User::class, 'kelas_id')->where('role', 'siswa');
}
}
