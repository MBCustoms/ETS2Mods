<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Report::class);

        $validated = $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Validate polymorphic type is allowed
        if (!in_array($validated['reportable_type'], ['App\Models\Mod'])) {
            abort(400, 'Invalid report type');
        }

        Report::create([
            'user_id' => $request->user()->id,
            'reportable_id' => $validated['reportable_id'],
            'reportable_type' => $validated['reportable_type'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully. Thank you for helping keep the community safe.');
    }
}
