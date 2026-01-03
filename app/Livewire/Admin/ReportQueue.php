<?php

namespace App\Livewire\Admin;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class ReportQueue extends Component
{
    use WithPagination;

    public $statusFilter = 'pending';
    public $severityFilter = '';

    public function updating($property)
    {
        if ($property === 'statusFilter' || $property === 'severityFilter') {
            $this->resetPage();
        }
    }

    public function markAsReviewed(Report $report, $status)
    {
        if (!auth()->user()->hasRole('admin')) {
             abort(403);
        }

        $report->update([
            'status' => $status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('report-updated', message: "Report #{$report->id} marked as {$status}.");
    }

    public function dismissAllForMod($modId, $modType)
    {
        if (!auth()->user()->hasRole('admin')) {
             abort(403);
        }

        Report::where('reportable_type', $modType)
            ->where('reportable_id', $modId)
            ->where('status', 'pending')
            ->update([
                'status' => 'resolved', // or 'reviewed' but implicit dismissal usually means resolved/ignored
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'admin_notes' => 'Bulk dismissed',
            ]);

        $this->dispatch('report-updated', message: "All pending reports for this item dismissed.");
    }

    public function render()
    {
        $reports = Report::with(['user', 'reportable'])
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->severityFilter, function ($q) {
                $q->where('severity', $this->severityFilter);
            })
            ->orderByRaw("FIELD(severity, 'critical', 'high', 'medium', 'low')") // Order by urgency
            ->latest()
            ->paginate(10);

        return view('livewire.admin.report-queue', [
            'reports' => $reports
        ])->layout('layouts.admin');
    }
}
