<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juz extends Model
{
    protected $table = 'juz'; // <- penting: nama tabel yang benar

    protected $fillable = ['nomor'];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    public function badges()
    {
        return $this->hasMany(Badge::class, 'juz_id');
    }
}