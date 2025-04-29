<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'graduate_experience_title',
        'graduate_experience_company',
        'graduate_experience_start_date',
        'graduate_experience_end_date',
        'graduate_experience_address',
        'graduate_experience_description',
        'graduate_experience_employment_type',
        'is_current'
    ];

    protected $casts = [
        'graduate_experience_start_date' => 'date',
        'graduate_experience_end_date' => 'date',
        'is_current' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}