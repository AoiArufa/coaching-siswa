<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coaching extends Model
{
    protected $fillable = [
        'guru_id',
        'murid_id',
        'tujuan',
        'deskripsi',
        'status',
        'user_id',
        'title',
        'description',
        'final_evaluation',
        'completed_at'
    ];

    public function sessions()
    {
        return $this->hasMany(CoachingSession::class);
    }

    public function progressPercentage()
    {
        $totalStages = CoachingStage::count();

        if ($totalStages == 0) {
            return 0;
        }

        $completedStages = $this->sessions()
            ->with('stage')
            ->get()
            ->pluck('stage.id')
            ->unique()
            ->count();

        return round(($completedStages / $totalStages) * 100);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
