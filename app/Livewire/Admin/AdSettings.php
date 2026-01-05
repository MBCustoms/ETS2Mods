<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class AdSettings extends Component
{
    public $enabled;
    public $test_mode;
    public $client_id;
    public $label_text;
    public $slots = [];

    public function mount()
    {
        $this->enabled = setting('ads.enabled', false);
        $this->test_mode = setting('ads.test_mode', true);
        $this->client_id = setting('ads.client_id', '');
        $this->label_text = setting('ads.label_text', 'Advertisement');
        
        $slots = setting('ads.slots', []);
        $this->slots = is_array($slots) ? $slots : json_decode($slots, true) ?? [];
        
        if (empty($this->slots)) {
            $this->slots = [
                'home_top' => '',
                'home_sidebar' => '',
                'home_inline' => '',
                'category_sidebar' => '',
                'category_inline' => '',
                'search_sidebar' => '',
                'search_inline' => '',
                'mod_detail_top' => '',
                'mod_detail_sidebar' => '',
                'mod_detail_inline' => '',
                'profile_sidebar' => '',
                'page_sidebar' => '',
                'create_sidebar' => '',
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'client_id' => 'nullable|string|max:255',
            'label_text' => 'required|string|max:100',
            'slots.*' => 'nullable|string|max:255',
        ]);

        setting_set('ads', 'enabled', $this->enabled ? '1' : '0', 'boolean');
        setting_set('ads', 'test_mode', $this->test_mode ? '1' : '0', 'boolean');
        setting_set('ads', 'client_id', $this->client_id, 'string');
        setting_set('ads', 'label_text', $this->label_text, 'string');
        setting_set('ads', 'slots', json_encode($this->slots), 'json');

        Cache::forget('app.settings');

        session()->flash('success', 'Ad settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.ad-settings')->layout('layouts.admin');
    }
}
