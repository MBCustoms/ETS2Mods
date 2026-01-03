<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->role, function ($query) {
                $query->role($this->role);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-table', [
            'users' => $users,
        ]);
    }

    public function toggleBan($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->hasRole('admin')) {
            return; // Cannot ban admins
        }

        if ($user->isBanned()) {
            $user->is_banned = false;
            $user->banned_at = null;
            $user->banned_reason = null;
        } else {
            $user->is_banned = true;
            $user->banned_at = now();
            // In a real app, we'd prompt for reason
            $user->banned_reason = 'Banned by admin'; 
        }
        
        $user->save();
    }
}
