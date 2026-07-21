<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = [
        'juz_id',
        'nomor_surat',
        'nama_surat',
        'total_ayat'
    ];

    /**
     * Relasi ke model Juz (Belongs To)
     * Surat ini termasuk ke dalam Juz tertentu.
     */
    public function juz()
    {
        return $this->belongsTo(Juz::class, 'juz_id');
    }

    /**
     * Relasi ke model Soal (One to Many)
     * Satu Surat memiliki banyak bank Soal.
     */
    public function soals()
    {
        return $this->hasMany(Soal::class, 'surat_id');
    }
}