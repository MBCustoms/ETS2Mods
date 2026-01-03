<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mod_id',
        'rating',
        'title',
        'review',
    ];

    /**
     * Relationship: Rating belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Rating belongs to mod
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }
}
