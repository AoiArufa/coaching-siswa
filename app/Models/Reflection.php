<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reflection extends Model
{
    protected $fillable = [
        'coaching_id',
        'hasil_perkembangan',
        'kendala',
        'evaluasi_metode',
        'catatan_guru',
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }
}
