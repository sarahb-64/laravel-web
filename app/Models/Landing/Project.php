<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'technologies',
        'links'
    ];

    protected $casts = [
        'technologies' => 'array',
        'links' => 'array'
    ];
}