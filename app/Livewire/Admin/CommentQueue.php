<?php

namespace App\Livewire\Admin;

use App\Models\ModComment;
use Livewire\Component;
use Livewire\WithPagination;

class CommentQueue extends Component
{
    use WithPagination;

    protected $listeners = ['deleteComment' => 'reject', 'approveAllMod' => 'approveAllForMod'];

    public $statusFilter = 'pending'; // pending, approved, rejected

    public function updating($property)
    {
        if ($property === 'statusFilter') {
            $this->resetPage();
        }
    }

    public function approve(ModComment $comment)
    {
        if (!auth()->user()->hasPermissionTo('moderate comments')) {
            abort(403);
        }

        $comment->update([
            'is_approved' => true,
        ]);

        // Recalculate mod rating stats
        $comment->mod->recalculateRating();

        session()->flash('message', "Comment #{$comment->id} approved.");
    }

    public function reject(ModComment $comment)
    {
        if (!auth()->user()->hasPermissionTo('moderate comments')) {
            abort(403);
        }

        $comment->delete();

        session()->flash('message', "Comment #{$comment->id} rejected and deleted.");
    }

    public function approveAllForMod($modId)
    {
        if (!auth()->user()->hasPermissionTo('moderate comments')) {
            abort(403);
        }

        $comments = ModComment::where('mod_id', $modId)
            ->where('is_approved', false)
            ->get();

        foreach ($comments as $comment) {
            $comment->update(['is_approved' => true]);
        }

        // Recalculate mod rating stats
        if ($comments->count() > 0) {
            $comments->first()->mod->recalculateRating();
        }

        session()->flash('message', "All pending comments for this mod approved.");
    }

    public function render()
    {
        $comments = ModComment::with(['user', 'mod', 'parent'])
            ->when($this->statusFilter === 'pending', function ($q) {
                $q->where('is_approved', false);
            })
            ->when($this->statusFilter === 'approved', function ($q) {
                $q->where('is_approved', true);
            })
            ->when($this->statusFilter === 'rejected', function ($q) {
                $q->onlyTrashed();
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.comment-queue', [
            'comments' => $comments
        ])->layout('layouts.admin');
    }
}
