<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachingSession extends Model
{
    protected $fillable = [
        'coaching_id',
        'coaching_stage_id',
        'session_date',
        'notes'
    ];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }

    public function stage()
    {
        return $this->belongsTo(CoachingStage::class, 'coaching_stage_id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
