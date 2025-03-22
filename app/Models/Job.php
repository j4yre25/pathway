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
        'job_type', 
        'experience_level',
        'skills', 
        'sector_id', 
        'category_id',
    ];


    protected $casts = [
        'skills' => 'array', // Cast skills to an array
    ];
}