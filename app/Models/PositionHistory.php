<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PositionHistory extends Model
{
    protected $fillable = ['keyword_id', 'position', 'search_engine'];

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}