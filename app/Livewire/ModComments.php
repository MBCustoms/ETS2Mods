<?php

namespace App\Livewire;

use App\Models\Mod;
use App\Models\ModComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ModComments extends Component
{
    use WithPagination, WithoutUrlPagination;

    public Mod $mod;
    public $content;
    public $replyToId = null;
    public $replyContent = '';

    public function mount(Mod $mod)
    {
        $this->mod = $mod;
    }

    public function postComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $this->mod->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        $this->resetPage(); // Go to page 1 to see new comment
        
        // Notify Mod Owner (if not self)
        if ($this->mod->user_id !== Auth::id()) {
            $this->mod->user->notify(new \App\Notifications\NewCommentNotification($this->mod->comments()->latest()->first()));
        }

        session()->flash('success', 'Comment posted successfully.');
    }

    public function setReplyTo($commentId)
    {
        $this->replyToId = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyToId = null;
        $this->replyContent = '';
    }

    public function postReply($commentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'replyContent' => 'required|string|min:3|max:1000',
        ]);

        $this->mod->comments()->create([
            'user_id' => Auth::id(),
            'parent_id' => $commentId, // Set parent ID
            'content' => $this->replyContent,
        ]);

        $this->replyToId = null;
        $this->replyContent = '';
        
        session()->flash('success', 'Reply posted successfully.');
    }

    public function delete($commentId)
    {
        $comment = ModComment::findOrFail($commentId);
        
        $this->authorize('delete', $comment); // Need Policy

        $comment->delete();
    }

    public function render()
    {
        $comments = $this->mod->comments()
            ->with(['user', 'replies.user']) // Eager load replies
            ->paginate(10);

        return view('livewire.mod-comments', [
            'comments' => $comments
        ]);
    }
}
