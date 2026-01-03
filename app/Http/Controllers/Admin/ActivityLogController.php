<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer', 'subject')
            ->latest()
            ->paginate(50);

        return view('admin.activity-log.index', compact('activities'));
    }
}
