<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\User;

class ModComment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'user_id',
        'mod_id',
        'parent_id',
        'content',
        'rating',
        'title',
        'is_pinned',
        'guest_name',
        'guest_email',
        'is_approved',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Relationship: Comment belongs to user (nullable for guest comments)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the display name for the comment author
     */
    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : ($this->guest_name ?? 'Anonymous');
    }

    /**
     * Get the display email for the comment author
     */
    public function getAuthorEmailAttribute()
    {
        return $this->user ? $this->user->email : ($this->guest_email ?? null);
    }

    /**
     * Check if comment is from a guest user
     */
    public function isGuest()
    {
        return is_null($this->user_id);
    }

    /**
     * Relationship: Comment belongs to mod
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    /**
     * Relationship: Comment has many replies (approved only)
     */
    public function replies()
    {
        return $this->hasMany(ModComment::class, 'parent_id')
            ->where('is_approved', true);
    }

    /**
     * Relationship: Comment has all replies (including unapproved, for admin)
     */
    public function allReplies()
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
