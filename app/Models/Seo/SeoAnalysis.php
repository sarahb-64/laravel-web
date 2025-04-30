<?php

namespace App\Models\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoAnalysis extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url',
        'page_load_time',
        'meta_title',
        'meta_description',
        'heading_structure',
        'image_alt_coverage',
        'internal_links',
        'external_links',
        'mobile_friendly',
        'ssl_enabled',
        'status',
        'error_message'
    ];

    protected $casts = [
        'heading_structure' => 'array',
        'internal_links' => 'array',
        'external_links' => 'array',
        'mobile_friendly' => 'boolean',
        'ssl_enabled' => 'boolean',
        'page_load_time' => 'decimal:2',
        'image_alt_coverage' => 'decimal:2'
    ];
}
