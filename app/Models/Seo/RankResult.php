<?php

// app/Models/Seo/RankResult.php
namespace App\Models\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RankResult extends Model
{
    protected $fillable = [
        'keyword_id',
        'domain',
        'position',
        'search_volume',
        'cpc',
        'competition',
        'rank_position'
    ];

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}
