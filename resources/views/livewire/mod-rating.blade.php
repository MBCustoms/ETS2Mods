<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-bold text-gray-900">User Reviews</h3>
             <div class="flex items-center mt-1">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= round($mod->reviews_avg) ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="ml-2 text-sm text-gray-500">{{ $mod->reviews_count }} ratings</p>
            </div>
        </div>
        
        @if($hasRated && !$showForm)
            <div class="text-sm text-green-600 font-medium">
                You rated this mod {{ $rating }} stars
                <button wire:click="$set('showForm', true)" class="ml-2 text-indigo-600 hover:text-indigo-800 underline">Edit</button>
            </div>
        @endif
    </div>

    @if(session()->has('rating_success'))
        <div class="mb-4 text-green-600 bg-green-50 p-3 rounded text-sm">
            {{ session('rating_success') }}
        </div>
    @endif

    @if(!auth()->check())
        <div class="text-sm text-gray-500 mb-4">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a> to rate this mod.
        </div>
    @elseif(!$hasRated || $showForm)
        <div class="border-t border-gray-100 pt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">{{ $hasRated ? 'Update your review' : 'Rate this mod' }}</h4>
            <div class="flex items-center space-x-1 mb-4">
                 @for($i = 1; $i <= 5; $i++)
                    <button wire:click="rate({{ $i }})" type="button" class="focus:outline-none">
                         <svg class="h-8 w-8 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                @endfor
            </div>

            @if($showForm)
                <form wire:submit.prevent="submit">
                    <div class="mb-3">
                        <label for="review_title" class="sr-only">Title</label>
                        <input wire:model="title" type="text" id="review_title" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Review Title (Optional)">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="review_body" class="sr-only">Review</label>
                        <textarea wire:model="review" id="review_body" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md p-2" placeholder="Write your review here..."></textarea>
                         @error('review') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Review
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>
