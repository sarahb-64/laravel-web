<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'price',
        'featured'
    ];

    protected $casts = [
        'featured' => 'boolean'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'price' => $this->price,
            'featured' => $this->featured
        ];
    }
}