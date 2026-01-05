<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstallerService
{
    /**
     * Check if the application is installed.
     */
    public function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }

    /**
     * Check server requirements.
     */
    public function checkRequirements(): array
    {
        $requirements = [
            'php' => [
                'version' => '8.2.0',
                'current' => phpversion(),
                'status' => version_compare(phpversion(), '8.2.0', '>='),
            ],
            'extensions' => [
                'bcmath' => extension_loaded('bcmath'),
                'ctype' => extension_loaded('ctype'),
                'curl' => extension_loaded('curl'),
                'dom' => extension_loaded('dom'),
                'fileinfo' => extension_loaded('fileinfo'),
                'json' => extension_loaded('json'),
                'mbstring' => extension_loaded('mbstring'),
                'openssl' => extension_loaded('openssl'),
                'pcre' => extension_loaded('pcre'),
                'pdo' => extension_loaded('pdo'),
                'tokenizer' => extension_loaded('tokenizer'),
                'xml' => extension_loaded('xml'),
            ],
        ];

        return $requirements;
    }

    /**
     * Check folder permissions.
     */
    public function checkPermissions(): array
    {
        $folders = [
            'storage/framework/' => storage_path('framework'),
            'storage/logs/' => storage_path('logs'),
            'bootstrap/cache/' => base_path('bootstrap/cache'),
        ];

        $permissions = [];

        foreach ($folders as $name => $path) {
            $permissions[] = [
                'folder' => $name,
                'is_writable' => File::isWritable($path),
            ];
        }

        return $permissions;
    }

    /**
     * Update .env file.
     */
    public function updateEnv(array $data): void
    {
        $path = base_path('.env');

        if (!File::exists($path)) {
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), $path);
            } else {
                File::put($path, '');
            }
        }

        $env = File::get($path);

        foreach ($data as $key => $value) {
            // Provide default if not exists
            if (!str_contains($env, $key . '=')) {
                $env .= "\n{$key}=";
            }

            // Wrap string with quotes if it contains spaces
            if (preg_match('/\s/', $value) && !str_starts_with($value, '"')) {
                $value = '"' . $value . '"';
            }

            $env = preg_replace("/^{$key}=(.*)$/m", "{$key}={$value}", $env);
        }

        File::put($path, $env);
    }

    /**
     * Mark application as installed.
     */
    public function markAsInstalled(): void
    {
        File::put(storage_path('installed'), 'INSTALLED ON ' . now());
    }
}
