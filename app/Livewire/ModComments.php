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
    public $rating = 0;
    public $title = '';
    public $replyToId = null;
    public $replyContent = '';
    
    // Guest fields
    public $guestName = '';
    public $guestEmail = '';
    public $replyGuestName = '';
    public $replyGuestEmail = '';
    

    public function mount(Mod $mod)
    {
        $this->mod = $mod;
    }

    public function rate(int $stars)
    {
        $this->rating = $stars;
    }

    public function postComment()
    {
        $isGuest = !Auth::check();
        
        $rules = [
            'content' => 'required|string|min:3|max:1000',
            'rating' => 'nullable|integer|min:0|max:5',
            'title' => 'nullable|string|max:100',
        ];

        if ($isGuest) {
            $rules['guestName'] = 'required|string|max:100';
            $rules['guestEmail'] = 'required|email|max:255';
        }
        
        if (setting('recaptcha.enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $this->validate($rules);

        // Create new comment (always create, no update for existing)
        $commentData = [
            'mod_id' => $this->mod->id,
            'content' => $this->content,
            'rating' => $this->rating > 0 ? $this->rating : null,
            'title' => $this->title ?: null,
            'is_approved' => false, // Requires admin approval
        ];

        if ($isGuest) {
            $commentData['guest_name'] = $this->guestName;
            $commentData['guest_email'] = $this->guestEmail;
        } else {
            $commentData['user_id'] = Auth::id();
        }

        $comment = ModComment::create($commentData);

        // Notify Mod Owner (if not self)
        if (!$isGuest && $this->mod->user_id !== Auth::id()) {
            $this->mod->user->notify(new \App\Notifications\NewCommentNotification($comment));
        }

        // Reset form
        $this->content = '';
        $this->rating = 0;
        $this->title = '';
        $this->guestName = '';
        $this->guestEmail = '';
        
        session()->flash('success', 'Your comment has been submitted and is pending approval.');

        // Recalculate mod rating stats from approved comments
        $this->mod->recalculateRating();
        $this->mod->refresh();
        $this->resetPage();
    }

    public function setReplyTo($commentId)
    {
        $this->replyToId = $commentId;
        $this->replyContent = '';
        $this->replyGuestName = '';
        $this->replyGuestEmail = '';
    }

    public function cancelReply()
    {
        $this->replyToId = null;
        $this->replyContent = '';
        $this->replyGuestName = '';
        $this->replyGuestEmail = '';
    }

    public function postReply($commentId)
    {
        $isGuest = !Auth::check();
        
        $rules = [
            'replyContent' => 'required|string|min:3|max:1000',
        ];

        if ($isGuest) {
            $rules['replyGuestName'] = 'required|string|max:100';
            $rules['replyGuestEmail'] = 'required|email|max:255';
        }
        
        if (setting('recaptcha.enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $this->validate($rules);

        $replyData = [
            'mod_id' => $this->mod->id,
            'parent_id' => $commentId,
            'content' => $this->replyContent,
            'is_approved' => false, // Requires admin approval
        ];

        if ($isGuest) {
            $replyData['guest_name'] = $this->replyGuestName;
            $replyData['guest_email'] = $this->replyGuestEmail;
        } else {
            $replyData['user_id'] = Auth::id();
        }

        ModComment::create($replyData);

        $this->replyToId = null;
        $this->replyContent = '';
        $this->replyGuestName = '';
        $this->replyGuestEmail = '';
        
        session()->flash('success', 'Your reply has been submitted and is pending approval.');
    }

    public function delete($commentId)
    {
        $comment = ModComment::findOrFail($commentId);
        
        // Only authenticated users can delete their own comments
        if (Auth::check()) {
            $this->authorize('delete', $comment);
        } else {
            abort(403, 'Unauthorized');
        }

        $comment->delete();
        
        // Recalculate mod rating stats
        $this->mod->recalculateRating();
        $this->mod->refresh();
    }

    public function render()
    {
        // Only show approved comments
        $comments = $this->mod->comments()
            ->with(['user', 'replies' => function($query) {
                $query->where('is_approved', true)->with('user');
            }])
            ->paginate(10);

        return view('livewire.mod-comments', [
            'comments' => $comments
        ]);
    }
}
