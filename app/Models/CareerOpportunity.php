<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunity extends Model
{
    use HasFactory;

    // Specify the fillable fields
    protected $fillable = [
        'title',       // Title of the career opportunity
        'description', // Description of the career opportunity (optional)
        'program_id',  // Foreign key linking to the Program model
    ];

    // Define the relationship with the Program model
    public function program()
{
    return $this->belongsTo(Program::class);
}

    
}