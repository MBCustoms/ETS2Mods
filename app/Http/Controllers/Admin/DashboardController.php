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
        
        $messageCount = \App\Models\ContactMessage::where('is_read', false)->count();
        $latestMessages = \App\Models\ContactMessage::latest()->take(10)->get();
        
        // Get pending mods (last 10)
        $pendingMods = \App\Models\Mod::where('status', 'pending')
            ->with(['user', 'modImages'])
            ->latest()
            ->take(10)
            ->get();

        // Get pending comments count
        $pendingCommentsCount = \App\Models\ModComment::where('is_approved', false)->count();
        $latestPendingComments = \App\Models\ModComment::where('is_approved', false)
            ->with(['user', 'mod'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'topModders', 'criticalReports', 'recentUsers', 'messageCount', 'latestMessages', 'pendingMods', 'pendingCommentsCount', 'latestPendingComments'));
    }
}
