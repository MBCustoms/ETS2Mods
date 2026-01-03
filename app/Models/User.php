<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'social_links',
        'is_verified',
        'shadow_banned_at',
        'warning_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'social_links' => 'array',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'shadow_banned_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relationship: User has many mods
     */
    public function mods()
    {
        return $this->hasMany(Mod::class);
    }

    /**
     * Relationship: User follows many things
     */
    public function following()
    {
        return $this->hasMany(Follow::class);
    }

    /**
     * Check if user follows a specific model
     */
    public function isFollowing($model)
    {
        return $this->following()
            ->where('followable_id', $model->id)
            ->where('followable_type', get_class($model))
            ->exists();
    }

    /**
     * Relationship: User has many reports
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Scope: Not banned users
     */
    public function scopeNotBanned($query)
    {
        return $query->where('is_banned', false);
    }

    /**
     * Scope: Banned users
     */
    public function scopeBanned($query)
    {
        return $query->where('is_banned', true);
    }

    /**
     * Check if user is banned
     */
    public function isBanned(): bool
    {
        return $this->is_banned;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is moderator
     */
    public function isModerator(): bool
    {
        return $this->hasRole('moderator');
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }
        return null;
    }
}
