<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Mod extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'youtube_videos' => 'array',
        'download_links' => 'array', // Pre-emptive add for next task
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'credits',
        'status',
        'is_featured',
        'rejection_reason',
        'views_count',
        'downloads_count',
        'published_at',
        'download_url', // Legacy/Main
        'youtube_videos',
        'reviews_avg',
        'reviews_count',
        'download_links', // New
    ];

    public function getYoutubeEmbedUrl($url)
    {
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        
        return isset($youtube_id) ? 'https://www.youtube.com/embed/' . $youtube_id : $url;
    }


    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mod) {
            if (empty($mod->slug)) {
                $mod->slug = Str::slug($mod->title);
                
                // Ensure unique slug
                $count = static::where('slug', 'LIKE', "{$mod->slug}%")->count();
                if ($count > 0) {
                    $mod->slug = "{$mod->slug}-" . ($count + 1);
                }
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relationship: Mod has many images
     */
    public function modImages()
    {
        return $this->hasMany(ModImage::class)->orderBy('order')->orderBy('is_main', 'desc');
    }

    /**
     * Get main image
     */
    public function getMainImageAttribute()
    {
        return $this->modImages()->where('is_main', true)->first() 
            ?? $this->modImages()->first();
    }

    /**
     * Get first image URL (for backward compatibility)
     */
    public function getFirstImageUrlAttribute(): ?string
    {
        $image = $this->main_image;
        return $image ? $image->url : null;
    }

    /**
     * Get first media URL (for backward compatibility with Spatie Media Library)
     */
    public function getFirstMediaUrl($collection = 'images'): ?string
    {
        return $this->first_image_url;
    }

    /**
     * Get all media (for backward compatibility)
     */
    public function getMedia(string $collectionName = 'default', callable|array $filters = []): \Illuminate\Support\Collection
    {
        if ($collectionName === 'images') {
            return $this->modImages ?? collect();
        }

        return collect();
    }

    /**
     * Relationship: Mod belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Mod belongs to category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Mod has many reports (polymorphic)
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Scope: Only approved mods
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('published_at');
    }

    /**
     * Scope: Only pending mods
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only rejected mods
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Only featured mods
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Latest mods
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope: Popular mods (by downloads)
     */
    public function scopePopular($query)
    {
        return $query->orderBy('downloads_count', 'desc');
    }

    /**
     * Relationship: Mod has many versions
     */
    public function versions()
    {
        return $this->hasMany(ModVersion::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relationship: Mod has one latest version
     */
    public function latestVersion()
    {
        return $this->hasOne(ModVersion::class)->ofMany('created_at', 'max');
    }

    /**
     * Accessor: Get download URL from latest version
     */
    public function getDownloadUrlAttribute()
    {
        return $this->latestVersion?->download_url;
    }

    /**
     * Accessor: Get file size from latest version
     */
    public function getFileSizeAttribute()
    {
        return $this->latestVersion?->file_size;
    }

    /**
     * Accessor: Get game version from latest version
     */
    public function getGameVersionAttribute()
    {
        return $this->latestVersion?->game_version;
    }

    /**
     * Increment views counter
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment downloads counter
     */
    public function incrementDownloads()
    {
        $this->increment('downloads_count');
        $this->latestVersion?->incrementDownloads();
    }

    /**
     * Relationship: Mod has many comments (approved only, top-level)
     */
    public function comments()
    {
        return $this->hasMany(ModComment::class)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->orderBy('is_pinned', 'desc')
            ->latest();
    }

    /**
     * Relationship: Mod has all comments (including unapproved, for admin)
     */
    public function allComments()
    {
        return $this->hasMany(ModComment::class)->whereNull('parent_id')->orderBy('is_pinned', 'desc')->latest();
    }

    /**
     * Relationship: Mod has many ratings
     */
    public function ratings()
    {
        return $this->hasMany(ModRating::class)->latest();
    }

    /**
     * Relationship: Mod has many followers
     */
    public function followers()
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    /**
     * Check if user follows this mod
     */
    public function isFollowedBy(?User $user)
    {
        if (!$user) return false;
        return $this->followers()->where('user_id', $user->id)->exists();
    }
    public function scopeSearch($query, $term)
    {
        $term = trim($term);
        if (!$term) {
            return $query;
        }
        // In boolean mode, we can use +,-,* operators.
        // For simplicity, we just look for exact match or words.
        return $query->whereRaw("MATCH(title, description) AGAINST(? IN BOOLEAN MODE)", [$term]);
    }
    /**
     * Recalculate mod's rating stats
     */
    public function recalculateRating()
    {
        $stats = $this->comments()
            ->where('is_approved', true)
            ->whereNotNull('rating')
            ->selectRaw('avg(rating) as average, count(*) as count')
            ->first();

        $this->update([
            'reviews_avg' => round($stats->average ?? 0, 2),
            'reviews_count' => $stats->count ?? 0,
        ]);

        // Also update the user's rating
        if ($this->user) {
            $this->user->recalculateRating();
        }
    }
}
