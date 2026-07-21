<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/PoinLog.php
class PoinLog extends Model
{
    protected $fillable = ['user_id', 'sumber', 'poin'];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}


