<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingsManager extends Component
{
    public $settings;

    public function mount()
    {
        $this->settings = Setting::all()->mapWithKeys(function ($item) {
            return ["{$item->group}.{$item->key}" => $item->value];
        })->toArray();
    }

    public function render()
    {
        $groupedSettings = Setting::all()->groupBy('group');
        
        return view('livewire.admin.settings-manager', [
            'groupedSettings' => $groupedSettings,
        ]);
    }

    public function save()
    {
        foreach ($this->settings as $key => $value) {
            [$group, $option] = explode('.', $key, 2);
            
            // Re-cast strictly if needed, but for now trusting string input or basic type casting
            Setting::set($group, $option, $value);
        }

        session()->flash('message', 'Settings saved successfully.');
    }
}
