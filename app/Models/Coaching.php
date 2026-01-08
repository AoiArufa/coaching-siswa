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
    ];

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
