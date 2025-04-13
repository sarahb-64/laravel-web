<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'description', 'percentage'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}