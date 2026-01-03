<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'mod_id',
        'parent_id',
        'content',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    /**
     * Relationship: Comment belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Comment belongs to mod
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    /**
     * Relationship: Comment has many replies
     */
    public function replies()
    {
        return $this->hasMany(ModComment::class, 'parent_id');
    }

    /**
     * Relationship: Comment belongs to parent comment
     */
    public function parent()
    {
        return $this->belongsTo(ModComment::class, 'parent_id');
    }
}
