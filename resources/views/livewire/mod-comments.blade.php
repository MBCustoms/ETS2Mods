<div class="mt-8 bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Comments</h3>

    <!-- Post Comment Form -->
    @auth
        <div class="mb-8">
            <form wire:submit.prevent="postComment">
                <div class="mb-4">
                    <label for="comment" class="sr-only">Your comment</label>
                    <textarea wire:model="content" id="comment" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-3" placeholder="Leave a comment..."></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Post Comment
                    </button>
                </div>
                 @if(session()->has('success'))
                    <span class="text-green-600 text-sm mt-2 block">{{ session('success') }}</span>
                @endif
            </form>
        </div>
    @else
        <div class="bg-gray-50 p-4 rounded-md text-center mb-8">
            <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">log in</a> to leave a comment.</p>
        </div>
    @endauth

    <!-- Comments List -->
    <div class="space-y-6">
        @forelse($comments as $comment)
            <div>
                <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                        <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm">
                            <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                             @if($comment->is_pinned)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pinned
                                </span>
                            @endif
                        </div>
                        <div class="mt-1 text-sm text-gray-500">
                            <p>{!! nl2br(e($comment->content)) !!}</p> <!-- Basic sanitization -->
                        </div>
                        <div class="mt-2 text-sm space-x-2">
                            <span class="text-gray-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                            @auth
                                <button wire:click="setReplyTo({{ $comment->id }})" class="text-gray-500 font-medium hover:text-gray-900">Reply</button>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                @if($replyToId === $comment->id)
                    <div class="mt-4 ml-12">
                         <form wire:submit.prevent="postReply({{ $comment->id }})">
                            <div class="mb-2">
                                <textarea wire:model="replyContent" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Write a reply..."></textarea>
                                @error('replyContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="cancelReply" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700">
                                    Reply
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Nested Replies -->
                @if($comment->replies->count() > 0)
                    <div class="mt-4 ml-12 space-y-4">
                        @foreach($comment->replies as $reply)
                             <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                     <span class="inline-block h-8 w-8 rounded-full overflow-hidden bg-gray-100">
                                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                          <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-900">{{ $reply->user->name }}</span>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        <p>{!! nl2br(e($reply->content)) !!}</p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-400">
                                        {{ $reply->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">No comments yet. Be the first to share your thoughts!</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
