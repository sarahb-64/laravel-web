<?php

namespace App\Models\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'keyword',
        'search_volume',
        'difficulty',
        'competition',
        'last_checked_at'
    ];

    protected $casts = [
        'search_volume' => 'integer',
        'difficulty' => 'integer',
        'competition' => 'float',
        'last_checked_at' => 'datetime'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByVolume($query)
    {
        return $query->orderBy('search_volume', 'desc');
    }
}