<?php

namespace App\Livewire\Admin;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class ReportTable extends Component
{
    use WithPagination;

    public $status = 'pending';

    protected $queryString = [
        'status' => ['except' => ''],
    ];

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $reports = Report::query()
            ->with(['user', 'reportable', 'reviewer'])
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.report-table', [
            'reports' => $reports,
        ]);
    }

    public function markAsReviewed($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->status = 'reviewed';
        $report->reviewed_by = auth()->id();
        $report->reviewed_at = now();
        $report->save();
    }

    public function markAsResolved($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->status = 'resolved';
        $report->reviewed_by = auth()->id();
        $report->reviewed_at = now();
        $report->save();
    }
}
