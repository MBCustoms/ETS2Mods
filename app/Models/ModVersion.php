<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mod_id',
        'version_number',
        'game_version', // e.g. 1.50
        'file_size',
        'download_url',
        'changelog',
        'downloads_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Version belongs to Mod
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    /**
     * Increment downloads counter
     */
    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }
}
