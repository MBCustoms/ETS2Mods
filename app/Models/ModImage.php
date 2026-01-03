<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModImage extends Model
{
    protected $fillable = [
        'mod_id',
        'path',
        'order',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    /**
     * Relationship: Image belongs to mod
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    /**
     * Get the full URL for the image
     */
    public function getUrlAttribute(): string
    {
        return asset($this->path);
    }
}
