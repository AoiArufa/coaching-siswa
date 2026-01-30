<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'coaching_id',
        'catatan',
        'refleksi',
        'tanggal',
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
