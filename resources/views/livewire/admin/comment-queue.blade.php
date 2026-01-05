<div>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Comment Queue</h2>
        <div class="flex space-x-4">
            <select wire:model.live="statusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                <option value="pending">Pending Approval</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-green-800 text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($comments as $comment)
                <li class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                @if($comment->isGuest())
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Guest
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Registered User
                                    </span>
                                @endif
                                <span class="text-sm text-gray-500">
                                    Posted {{ $comment->created_at->diffForHumans() }}
                                    @if($comment->isGuest())
                                        by {{ $comment->guest_name }} ({{ $comment->guest_email }})
                                    @else
                                        by {{ $comment->user->name }}
                                    @endif
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <span class="text-gray-500 text-sm uppercase tracking-wide mr-2">Mod:</span>
                                <a href="{{ route('mods.show', $comment->mod_id) }}" target="_blank" class="hover:underline text-orange-600">
                                    {{ $comment->mod->title }}
                                </a>
                            </h3>

                            @if($comment->parent)
                                <div class="mb-2 text-sm text-gray-600">
                                    <span class="font-medium">Reply to:</span> 
                                    {{ $comment->parent->isGuest() ? $comment->parent->guest_name : $comment->parent->user->name }}
                                </div>
                            @endif

                            @if($comment->rating)
                                <div class="flex items-center mb-2">
                                    <span class="text-sm text-gray-700 mr-2">Rating:</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            @endif

                            @if($comment->title)
                                <div class="mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $comment->title }}</span>
                                </div>
                            @endif
                            
                            <div class="mt-2 text-sm text-gray-800 bg-gray-50 p-3 rounded">
                                {!! nl2br(e($comment->content)) !!}
                            </div>
                        </div>

                        <div class="ml-6 flex flex-col items-end space-y-2">
                             @if($statusFilter === 'pending')
                                <div class="flex space-x-2">
                                    <button wire:click="approve({{ $comment->id }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                        Approve
                                    </button>
                                    <button wire:click="$dispatch('confirm-action', { 
                                                title: 'Reject Comment?', 
                                                text: 'Are you sure you want to reject and delete this comment?', 
                                                icon: 'error', 
                                                confirmButtonText: 'Yes, reject it!',
                                                method: 'deleteComment',
                                                params: {{ $comment->id }}
                                            })" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                        Reject
                                    </button>
                                </div>
                                <button wire:click="$dispatch('confirm-action', { 
                                            title: 'Approve All?', 
                                            text: 'Are you sure you want to approve all pending comments for this mod?', 
                                            icon: 'warning', 
                                            confirmButtonText: 'Yes, approve all!',
                                            method: 'approveAllMod',
                                            params: {{ $comment->mod_id }}
                                        })" 
                                        class="text-xs text-gray-500 hover:text-gray-700 underline mt-2">
                                    Approve all for this mod
                                </button>
                             @elseif($statusFilter === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approved
                                </span>
                                <span class="text-xs text-gray-400">{{ $comment->updated_at->diffForHumans() }}</span>
                             @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rejected
                                </span>
                                <span class="text-xs text-gray-400">{{ $comment->deleted_at->diffForHumans() }}</span>
                             @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-12 text-center text-gray-500">
                    No comments match your filters.
                </li>
            @endforelse
        </ul>
    </div>
    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
