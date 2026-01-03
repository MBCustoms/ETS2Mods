<?php

namespace App\Services;

use App\Models\Mod;
use App\Models\ModRating;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RatingService
{
    /**
     * Submit a rating for a mod
     */
    public function submitRating(Mod $mod, User $user, int $rating, ?string $title = null, ?string $review = null): ModRating
    {
        return DB::transaction(function () use ($mod, $user, $rating, $title, $review) {
            $modRating = ModRating::updateOrCreate(
                ['user_id' => $user->id, 'mod_id' => $mod->id],
                [
                    'rating' => $rating,
                    'title' => $title,
                    'review' => $review,
                ]
            );

            $this->updateModRatingStats($mod);

            return $modRating;
        });
    }

    /**
     * Update cached rating stats on the Mod model
     */
    public function updateModRatingStats(Mod $mod): void
    {
        // Calculate stats
        $stats = $mod->ratings()
            ->selectRaw('avg(rating) as average, count(*) as count')
            ->first();

        $avg = $stats->average ?? 0;
        $count = $stats->count ?? 0;

        // Update Mod table
        $mod->update([
            'reviews_avg' => round($avg, 2),
            'reviews_count' => $count,
        ]);
        
        // Clear specific caches if any (future proofing)
    }

    /**
     * Get user's rating for a mod
     */
    public function getUserRating(Mod $mod, User $user): ?ModRating
    {
        return $mod->ratings()->where('user_id', $user->id)->first();
    }
}
