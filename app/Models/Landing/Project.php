<?php

namespace App\Models\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 
        'name', 
        'url', 
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('url', 'like', "%{$search}%");
        });
    }
}