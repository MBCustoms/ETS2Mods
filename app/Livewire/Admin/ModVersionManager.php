<?php

namespace App\Livewire\Admin;

use App\Models\Mod;
use App\Models\ModVersion;
use Livewire\Component;

class ModVersionManager extends Component
{
    public Mod $mod;
    public $versions;
    
    // Form fields
    public $version_number;
    public $game_version;
    public $file_size;
    public $download_url;
    public $changelog;

    protected $rules = [
        'version_number' => 'required|string|max:20',
        'game_version' => 'nullable|string|max:50',
        'file_size' => 'nullable|string|max:50',
        'download_url' => 'required|url',
        'changelog' => 'nullable|string',
    ];

    public function mount(Mod $mod)
    {
        $this->mod = $mod;
        $this->refreshVersions();
    }

    public function refreshVersions()
    {
        $this->versions = $this->mod->versions()->get();
    }

    public function store()
    {
        $this->validate();

        $this->mod->versions()->create([
            'version_number' => $this->version_number,
            'game_version' => $this->game_version,
            'file_size' => $this->file_size,
            'download_url' => $this->download_url,
            'changelog' => $this->changelog,
            'is_active' => true,
        ]);

        $this->mod->update([
            'published_at' => now(), // bump simplified
        ]);

        $this->reset(['version_number', 'game_version', 'file_size', 'download_url', 'changelog']);
        $this->refreshVersions();
        
        session()->flash('success', 'New version added successfully.');
    }

    public function toggleStatus($versionId)
    {
        $version = ModVersion::findOrFail($versionId);
        $version->update(['is_active' => !$version->is_active]);
        $this->refreshVersions();
    }

    public function delete($versionId)
    {
        $version = ModVersion::findOrFail($versionId);
        $version->delete();
        $this->refreshVersions();
    }

    public function render()
    {
        return view('livewire.admin.mod-version-manager');
    }
}
