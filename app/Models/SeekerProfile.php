<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'city',
        'skills',
        'cv_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return filled($this->headline) && filled($this->city) && filled($this->skills);
    }
}
