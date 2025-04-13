<?php

namespace App\Models\Seo;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['user_id', 'name', 'url', 'description'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }
}