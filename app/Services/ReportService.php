<?php

namespace App\Services;

use App\Models\Mod;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportService
{
    /**
     * Create a new report and check for auto-flagging thresholds
     */
    public function report(User $reporter, Model $reportable, string $reason, ?string $description = null, string $severity = 'low', array $metadata = []): Report
    {
        $report = Report::create([
            'user_id' => $reporter->id,
            'reportable_type' => get_class($reportable),
            'reportable_id' => $reportable->id,
            'reason' => $reason,
            'description' => $description,
            'severity' => $severity,
            'metadata' => $metadata,
            'status' => 'pending',
        ]);

        $this->checkAutoFlagging($reportable);

        return $report;
    }

    /**
     * Check if the reportable model should be auto-flagged
     */
    protected function checkAutoFlagging(Model $reportable): void
    {
        // Only applies to Mods for now
        if (!($reportable instanceof Mod)) {
            return;
        }

        // Count pending reports in last 24 hours
        $recentReports = Report::where('reportable_type', Mod::class)
            ->where('reportable_id', $reportable->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subDay())
            ->get();

        $highSeverityCount = $recentReports->whereIn('severity', ['high', 'critical'])->count();
        $totalCount = $recentReports->count();

        // Thresholds: 3+ High/Critical OR 5+ Total in 24h
        if ($highSeverityCount >= 3 || $totalCount >= 5) {
            // Auto-flag
            $reportable->update(['status' => 'review_pending']); // Or 'on_hold' if we had it
            
            // Here we could also notify admins via email/slack
            // Notification::send(User::role('admin')->get(), new ModAutoFlaggedNotification($reportable));
        }
    }
}
