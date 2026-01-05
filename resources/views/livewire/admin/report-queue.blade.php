<div>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Report Queue</h2>
        <div class="flex space-x-4">
            <select wire:model.live="statusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                <option value="pending">Pending</option>
                <option value="reviewed">Reviewed</option>
                <option value="resolved">Resolved</option>
                <option value="">All Statuses</option>
            </select>
            <select wire:model.live="severityFilter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                <option value="">All Severities</option>
                <option value="critical">Critical</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($reports as $report)
                <li class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $report->severity === 'critical' ? 'bg-red-100 text-red-800' : 
                                       ($report->severity === 'high' ? 'bg-orange-100 text-orange-800' : 
                                       ($report->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($report->severity) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    Reported {{ $report->created_at->diffForHumans() }} by {{ $report->user->name }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-900">
                                <span class="text-gray-500 text-sm uppercase tracking-wide mr-2">{{ class_basename($report->reportable_type) }}</span>
                                @if($report->reportable)
                                    <a href="{{ route('mods.show', $report->reportable_id) }}" target="_blank" class="hover:underline text-orange-600">
                                        {{ $report->reportable->title ?? 'Item #' . $report->reportable_id }}
                                    </a>
                                @else
                                    <span class="text-red-500 italic">Deleted Item</span>
                                @endif
                            </h3>
                            
                            <div class="mt-2 text-sm text-gray-800 bg-gray-50 p-3 rounded">
                                <span class="font-bold block text-xs text-gray-500 uppercase mb-1">Reason: {{ $report->reason }}</span>
                                {{ $report->description }}
                            </div>
                        </div>

                        <div class="ml-6 flex flex-col items-end space-y-2">
                             @if($report->status === 'pending')
                                <div class="flex space-x-2">
                                    <button wire:click="markAsReviewed({{ $report->id }}, 'resolved')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">
                                        Resolve
                                    </button>
                                    <button wire:click="markAsReviewed({{ $report->id }}, 'reviewed')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                        Review
                                    </button>
                                </div>
                                @if($report->reportable)
                                    <button wire:confirm="Are you sure you want to dismiss all pending reports for this item?" 
                                            wire:click="dismissAllForMod({{ $report->reportable_id }}, '{{ addslashes($report->reportable_type) }}')" 
                                            class="text-xs text-gray-500 hover:text-gray-700 underline mt-2">
                                        Dismiss all for this item
                                    </button>
                                @endif
                             @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($report->status) }}
                                </span>
                                <span class="text-xs text-gray-400">by {{ $report->reviewer?->name }}</span>
                             @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-12 text-center text-gray-500">
                    No reports match your filters.
                </li>
            @endforelse
        </ul>
    </div>
    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
