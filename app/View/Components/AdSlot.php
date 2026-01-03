<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdSlot extends Component
{
    public $name;
    protected $adService;

    public function __construct($name, \App\Services\AdService $adService)
    {
        $this->name = $name;
        $this->adService = $adService;
    }

    public function render(): View|Closure|string
    {
        $content = $this->adService->getAdContent($this->name);
        
        return view('components.ad-slot', compact('content'));
    }
}
