<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'job_title',
        'description',
        'requirements',
        'location',
        'salary',
        'vacancy',
        'employ_type',
        'experience_level',
        'skills',
    ];
}