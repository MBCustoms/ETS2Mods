<div class="mt-8 bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">Reviews & Comments</h3>
        @if($mod->comments()->whereNotNull('rating')->count() > 0)
            <div class="flex items-center">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= round($mod->comments()->whereNotNull('rating')->avg('rating') ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="ml-2 text-sm text-gray-500">{{ $mod->comments()->whereNotNull('rating')->count() }} reviews</p>
            </div>
        @endif
    </div>

    <!-- Post Review/Comment Form -->
    <div class="mb-8 border-b border-gray-200 pb-6">
        @if(session()->has('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                <span class="text-green-600 text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="postComment">
            @auth
                <div class="mb-4 text-sm text-gray-600">
                    <span class="font-medium">Commenting as:</span> {{ Auth::user()->name }}
                </div>
            @else
                <!-- Guest Name and Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="guestName" class="block text-sm font-medium text-gray-700 mb-1">Your Name <span class="text-red-500">*</span></label>
                        <input wire:model="guestName" type="text" id="guestName" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Enter your name" required>
                        @error('guestName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="guestEmail" class="block text-sm font-medium text-gray-700 mb-1">Your Email <span class="text-red-500">*</span></label>
                        <input wire:model="guestEmail" type="email" id="guestEmail" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Enter your email" required>
                        @error('guestEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endauth

            <!-- Rating Stars -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating (Optional)</label>
                <div class="flex items-center space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                        <button wire:click.prevent="rate({{ $i }})" type="button" class="focus:outline-none transition">
                            <svg class="h-8 w-8 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    @endfor
                </div>
                @if($rating > 0)
                    <p class="mt-1 text-xs text-gray-500">Selected: {{ $rating }} star{{ $rating > 1 ? 's' : '' }}</p>
                @endif
                @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Review Title (only if rating is given) -->
            @if($rating > 0)
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Review Title (Optional)</label>
                    <input wire:model="title" type="text" id="title" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="e.g. Great mod!">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Comment/Review Content -->
            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">{{ $rating > 0 ? 'Your Review' : 'Your Comment' }} <span class="text-red-500">*</span></label>
                <textarea wire:model="content" id="comment" rows="4" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-3" placeholder="{{ $rating > 0 ? 'Write your review here...' : 'Leave a comment...' }}" required></textarea>
                @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- reCAPTCHA -->
            @if(setting('recaptcha.enabled'))
                <div class="mb-4">
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @endif
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    {{ $rating > 0 ? 'Submit Review' : 'Post Comment' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-6">
        @forelse($comments as $comment)
            <div>
                <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                        @if($comment->user && $comment->user->avatar_url)
                            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->author_name }}" class="h-10 w-10 rounded-full">
                        @else
                            <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                  <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="text-sm flex items-center justify-between">
                            <div class="flex items-center flex-wrap gap-2">
                                @if($comment->user)
                                    <a href="{{ route('users.show', $comment->user) }}" class="font-medium text-gray-900 hover:text-orange-600 hover:underline">
                                        {{ $comment->author_name }}
                                    </a>
                                    @if($comment->user->is_verified)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800" title="Verified Author">
                                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Verified
                                        </span>
                                    @endif
                                    @if($comment->user_id === $mod->user_id)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800" title="Mod Owner">
                                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                            Author
                                        </span>
                                    @endif
                                @else
                                    <span class="font-medium text-gray-900">{{ $comment->author_name }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Guest</span>
                                @endif

                                @if($comment->is_pinned)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pinned
                                    </span>
                                @endif
                            </div>
                            @if($comment->rating)
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            @endif
                        </div>
                        @if($comment->title)
                            <div class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $comment->title }}
                            </div>
                        @endif
                        <div class="mt-1 text-sm text-gray-500">
                            <p>{!! nl2br(e($comment->content)) !!}</p>
                        </div>
                        <div class="mt-2 text-sm space-x-2">
                            <span class="text-gray-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                            <button wire:click="setReplyTo({{ $comment->id }})" class="text-gray-500 font-medium hover:text-gray-900">Reply</button>
                            @auth
                                @if($comment->user_id === Auth::id())
                                    <button wire:click="delete({{ $comment->id }})" wire:confirm="Are you sure you want to delete this comment?" class="text-red-500 font-medium hover:text-red-700">Delete</button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                @if($replyToId === $comment->id)
                    <div class="mt-4 ml-12">
                         <form wire:submit.prevent="postReply({{ $comment->id }})">
                            @auth
                                <div class="mb-2 text-xs text-gray-600">
                                    <span class="font-medium">Replying as:</span> {{ Auth::user()->name }}
                                </div>
                            @else
                                <!-- Guest Name and Email for Reply -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <input wire:model="replyGuestName" type="text" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Your Name" required>
                                        @error('replyGuestName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <input wire:model="replyGuestEmail" type="email" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Your Email" required>
                                        @error('replyGuestEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endauth
                            <div class="mb-2">
                                <textarea wire:model="replyContent" rows="2" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Write a reply..." required></textarea>
                                @error('replyContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @if(setting('recaptcha.enabled'))
                                <div class="mb-2">
                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="cancelReply" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-orange-600 hover:bg-orange-700">
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
                                    @if($reply->user && $reply->user->avatar_url)
                                        <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->author_name }}" class="h-8 w-8 rounded-full">
                                    @else
                                        <span class="inline-block h-8 w-8 rounded-full overflow-hidden bg-gray-100">
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                              <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm flex items-center flex-wrap gap-2">
                                        @if($reply->user)
                                            <a href="{{ route('users.show', $reply->user) }}" class="font-medium text-gray-900 hover:text-orange-600 hover:underline">
                                                {{ $reply->author_name }}
                                            </a>
                                            @if($reply->user->is_verified)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800" title="Verified Author">
                                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    Verified
                                                </span>
                                            @endif
                                            @if($reply->user_id === $mod->user_id)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800" title="Mod Owner">
                                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                                    Author
                                                </span>
                                            @endif
                                        @else
                                            <span class="font-medium text-gray-900">{{ $reply->author_name }}</span>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Guest</span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        <p>{!! nl2br(e($reply->content)) !!}</p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-400 space-x-2">
                                        <span>{{ $reply->created_at->diffForHumans() }}</span>
                                        @auth
                                            @if($reply->user_id === Auth::id())
                                                <button wire:click="delete({{ $reply->id }})" wire:confirm="Are you sure you want to delete this reply?" class="text-red-500 hover:text-red-700">Delete</button>
                                            @endif
                                        @endauth
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

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', ({ el, component }) => {
            // Reset reCAPTCHA after Livewire updates
            if (typeof grecaptcha !== 'undefined' && document.querySelector('.g-recaptcha')) {
                grecaptcha.reset();
            }
        });
    });
</script>
@endpush
