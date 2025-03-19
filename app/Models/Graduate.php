<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'program_id',
        'year_graduated',
        'employment_status',
        'current_job_title',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id'); // FIX: It should reference Program, not Institution.
    }
    
}


