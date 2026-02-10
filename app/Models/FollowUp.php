<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'coaching_id',
        'judul',
        'deskripsi',
        'target_tanggal',
        'status',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }
}
