<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'severity',
        'metadata',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relationship: Report belongs to user (reporter)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Report belongs to reviewer (admin/moderator)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Polymorphic relationship: Get the reportable model
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * Scope: Only pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only reviewed reports
     */
    public function scopeReviewed($query)
    {
        return $query->whereIn('status', ['reviewed', 'resolved']);
    }
}
