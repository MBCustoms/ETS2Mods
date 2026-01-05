<?php

namespace App\Services;

use App\Models\Mod;
use App\Models\User;
use App\Models\Report;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    /**
     * Get overview statistics for the dashboard.
     */
    public function getOverviewStats(): array
    {
        // Cache stats for 10 minutes to reduce DB load
        return Cache::remember('admin_dashboard_stats', 600, function () {
            return [
                'total_mods' => Mod::count(),
                'active_mods' => Mod::approved()->count(),
                'pending_mods' => Mod::where('status', 'pending')->count(),
                
                'total_users' => User::count(),
                'verified_users' => User::where('is_verified', true)->count(),
                
                'total_downloads' => DownloadLog::count(), // Use log count for accuracy or sum of mods downloads_count
                'recent_downloads' => DownloadLog::where('created_at', '>=', now()->subDay())->count(),
                
                'pending_reports' => Report::where('status', 'pending')->count(),
                'critical_reports' => Report::where('status', 'pending')->where('severity', 'critical')->count(),
            ];
        });
    }

    /**
     * Get data for a basic activity chart (last 7 days).
     */
    public function getWeeklyActivity(): array
    {
        return Cache::remember('admin_dashboard_chart', 3600, function () {
            $days = collect(range(6, 0))->map(function ($daysAgo) {
                return now()->subDays($daysAgo)->format('Y-m-d');
            });

            $downloads = [];
            $uploads = [];

            foreach ($days as $date) {
                $downloads[] = DownloadLog::whereDate('created_at', $date)->count();
                $uploads[] = Mod::whereDate('created_at', $date)->count();
            }

            return [
                'labels' => $days->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))->toArray(),
                'downloads' => $downloads,
                'uploads' => $uploads,
            ];
        });
    }

    public function getTopModders(int $limit = 5)
    {
        return Cache::remember('admin_top_modders', 3600, function () use ($limit) {
            return User::withCount('mods')
                ->has('mods')
                ->orderByDesc('mods_count')
                ->take($limit)
                ->get();
        });
    }

    public function getCriticalReports(int $limit = 5)
    {
        return Report::with(['reportable', 'user'])
            ->whereIn('severity', ['high', 'critical'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();
    }
    
    public function getRecentUsers(int $limit = 5)
    {
        return User::orderByDesc('created_at')->take($limit)->get();
    }

    public function getTopDownloadedMods(int $limit = 5)
    {
        return Cache::remember('admin_top_downloads', 3600, function () use ($limit) {
            return DownloadLog::select('mod_id', \DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('mod_id')
                ->orderByDesc('total')
                ->with('mod')
                ->take($limit)
                ->get();
        });
    }

    public function getUserRegistrationStats(int $days = 30)
    {
        return Cache::remember('admin_user_stats', 3600, function () use ($days) {
            $stats = User::selectRaw('DATE(created_at) as date, count(*) as count')
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $labels = [];
            $data = [];
            
            // Fill missing days
            $period = \Carbon\CarbonPeriod::create(now()->subDays($days), now());
            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $labels[] = $date->format('M d');
                $record = $stats->firstWhere('date', $formattedDate);
                $data[] = $record ? $record->count : 0;
            }

            return ['labels' => $labels, 'data' => $data];
        });
    }
}
