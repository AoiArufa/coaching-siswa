<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'user_id',
        'coaching_id',
        'tanggal',
        'catatan',
        'refleksi',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
