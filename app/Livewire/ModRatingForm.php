<?php

namespace App\Livewire;

use App\Models\Mod;
use App\Models\ModRating;
use App\Services\RatingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ModRatingForm extends Component
{
    public Mod $mod;
    public int $rating = 0;
    public ?string $title = null;
    public ?string $review = null;
    public bool $hasRated = false;
    public bool $showForm = false;

    public function mount(Mod $mod)
    {
        $this->mod = $mod;
        
        if (Auth::check()) {
            $existingRating = app(RatingService::class)->getUserRating($mod, Auth::user());
            if ($existingRating) {
                $this->hasRated = true;
                $this->rating = $existingRating->rating;
                $this->title = $existingRating->title;
                $this->review = $existingRating->review;
            }
        }
    }

    public function rate(int $stars)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->rating = $stars;
        $this->showForm = true;
    }

    public function submit(RatingService $ratingService)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'review' => 'nullable|string|max:1000',
        ]);

        $ratingService->submitRating(
            $this->mod,
            Auth::user(),
            $this->rating,
            $this->title,
            $this->review
        );

        $this->hasRated = true; // simplified success state
        $this->showForm = false;
        
        $this->dispatch('ratingUpdated'); // optional event
        
        session()->flash('rating_success', 'Thank you for your review!');
    }

    public function render()
    {
        return view('livewire.mod-rating');
    }
}

