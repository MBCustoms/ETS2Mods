<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analytics;

    public function __construct(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index()
    {
        // Re-use the analytics service to populate a dedicated page
        // We can expand this service later for more detailed stats
        $stats = $this->analytics->getOverviewStats();
        $chartData = $this->analytics->getWeeklyActivity(); // Could be monthly or yearly for this page
        $topModders = $this->analytics->getTopModders(10); // Show more
        $recentUsers = $this->analytics->getRecentUsers(20); // Show more
        $topDownloads = $this->analytics->getTopDownloadedMods(10);
        $registrationStats = $this->analytics->getUserRegistrationStats(30);

        return view('admin.analytics.index', compact('stats', 'chartData', 'topModders', 'recentUsers', 'topDownloads', 'registrationStats'));
    }
}
