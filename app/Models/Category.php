<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{


    public function sector() {
        return $this->belongsTo(Sector::class);
    }

    protected $fillable = [
        'name',
        'user_id',
        'sector_id'
   
        
    ];
}
