<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{

    protected $table = 'news_cache';

    protected $fillable = [
        'country_id',
        'title',
        'description',
        'content',
        'source',
        'url',
        'sentiment',
        'positive_score',
        'negative_score',
        'published_at',  // FIX: was missing from fillable
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}