<div>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Reports</h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
             <select wire:model.live="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                <option value="pending">Pending</option>
                <option value="reviewed">Reviewed</option>
                <option value="resolved">Resolved</option>
                <option value="">All</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reports as $report)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($report->reportable_type === 'App\Models\Mod')
                                                Mod: <a href="{{ route('mods.show', $report->reportable) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $report->reportable->title }}</a>
                                            @else
                                                {{ $report->reportable_type }} #{{ $report->reportable_id }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <span class="font-bold">{{ $report->reason }}</span><br>
                                        {{ Str::limit($report->description, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $report->status === 'pending' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $report->status === 'reviewed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $report->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($report->status === 'pending')
                                            <button wire:click="markAsReviewed({{ $report->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">Review</button>
                                        @endif
                                        @if($report->status !== 'resolved')
                                            <button wire:click="markAsResolved({{ $report->id }})" class="text-green-600 hover:text-green-900">Resolve</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
