<?php

namespace App\Livewire;

use App\Models\Mod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowToggle extends Component
{
    public $model; // The model being followed (Mod or User)
    public bool $isFollowing = false;

    public function mount($model)
    {
        $this->model = $model;
        $this->checkFollowStatus();
    }

    public function checkFollowStatus()
    {
        if (Auth::check()) {
            $this->isFollowing = Auth::user()->isFollowing($this->model);
        }
    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isFollowing) {
            Auth::user()->following()
                ->where('followable_id', $this->model->id)
                ->where('followable_type', get_class($this->model))
                ->delete();
            $this->isFollowing = false;
        } else {
            Auth::user()->following()->create([
                'followable_id' => $this->model->id,
                'followable_type' => get_class($this->model),
            ]);
            $this->isFollowing = true;
        }
    }

    public function render()
    {
        return view('livewire.follow-toggle');
    }
}
