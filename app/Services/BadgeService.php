<?php

namespace App\Services;

use App\Models\User;

class BadgeService
{
    /**
     * Calculate badges for a user
     * 
     * @param User $user
     * @return array
     */
    public function getBadges(User $user): array
    {
        $badges = [];

        // Verified
        if ($user->is_verified) {
            $badges[] = [
                'id' => 'verified',
                'name' => 'Verified',
                'icon' => 'check-circle',
                'color' => 'blue',
                'description' => 'Verified Identity'
            ];
        }

        // Pioneer (Joined before 2025, or early adopter)
        // Adjust date as per requirement. Assuming 'Early 2025' or 'Before Launch'.
        // Let's say before Jan 1, 2025.
        if ($user->created_at && $user->created_at->lt('2025-01-01')) {
            $badges[] = [
                'id' => 'pioneer',
                'name' => 'Pioneer',
                'icon' => 'sparkles',
                'color' => 'purple',
                'description' => 'Early Adopter'
            ];
        }

        // Modder (Has > 1 approved mod)
        // We need to count approved mods.
        $activeModsCount = $user->mods()->approved()->count();
        if ($activeModsCount >= 1) {
            $badges[] = [
                'id' => 'modder',
                'name' => 'Modder',
                'icon' => 'cube',
                'color' => 'green',
                'description' => 'Published a Mod'
            ];
        }

        // Elite (Total Downloads > 10k)
        // We sum downloads_count of all mods.
        $totalDownloads = $user->mods()->sum('downloads_count');
        if ($totalDownloads > 10000) {
            $badges[] = [
                'id' => 'elite',
                'name' => 'Elite',
                'icon' => 'star',
                'color' => 'yellow',
                'description' => '10k+ Downloads'
            ];
        } elseif ($totalDownloads > 1000) {
             $badges[] = [
                'id' => 'rising_star',
                'name' => 'Rising Star',
                'icon' => 'trending-up',
                'color' => 'orange',
                'description' => '1k+ Downloads'
            ];
        }

        return $badges;
    }
}
