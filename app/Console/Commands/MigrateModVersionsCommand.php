<?php

namespace App\Console\Commands;

use App\Models\Mod;
use App\Models\ModVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateModVersionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:mod-versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing mod data relative to versioning to the new mod_versions table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of mod versions...');

        $mods = Mod::doesntHave('versions')->get();

        if ($mods->isEmpty()) {
            $this->info('No mods found requiring migration.');
            return;
        }

        $bar = $this->output->createProgressBar($mods->count());
        $bar->start();

        foreach ($mods as $mod) {
            try {
                // Determine a default version number if none exists in title/description
                // For simplicity, we assign v1.0.0 to all existing mods
                
                // Check if deprecated fields have data
                if (!empty($mod->download_url)) {
                    ModVersion::create([
                        'mod_id' => $mod->id,
                        'version_number' => '1.0.0', // Default for migration
                        'game_version' => $mod->game_version,
                        'file_size' => $mod->file_size,
                        'download_url' => $mod->download_url,
                        'changelog' => 'Initial release (migrated)',
                        'downloads_count' => $mod->downloads_count ?? 0,
                        'is_active' => true,
                    ]);
                }
            } catch (\Exception $e) {
                $this->error("Failed to migrate mod ID {$mod->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Mod versions migration completed successfully.');
    }
}
