<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'coaching_id',
        'user_id',
        'title',
        'description',
        'file_path',
        'external_link',
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
