<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    protected $analytics;

    public function __construct(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index()
    {
        $stats = $this->analytics->getOverviewStats();
        $chartData = $this->analytics->getWeeklyActivity();
        $topModders = $this->analytics->getTopModders();
        $criticalReports = $this->analytics->getCriticalReports();
        $recentUsers = $this->analytics->getRecentUsers();

        return view('admin.dashboard', compact('stats', 'chartData', 'topModders', 'criticalReports', 'recentUsers'));
    }
}
