<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'followable_id',
        'followable_type',
    ];

    /**
     * Relationship: Follow belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Get the parent followable model
     */
    public function followable()
    {
        return $this->morphTo();
    }
}
