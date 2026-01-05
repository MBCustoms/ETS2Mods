<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['group' => 'ads', 'key' => 'enabled', 'value' => 'false', 'type' => 'boolean', 'is_public' => true],
            ['group' => 'ads', 'key' => 'test_mode', 'value' => 'true', 'type' => 'boolean', 'is_public' => false],
            ['group' => 'ads', 'key' => 'client_id', 'value' => '', 'type' => 'string', 'is_public' => false],
            ['group' => 'ads', 'key' => 'label_text', 'value' => 'Advertisement', 'type' => 'string', 'is_public' => true],
            ['group' => 'ads', 'key' => 'slots', 'value' => json_encode([
                'home_top' => '',
                'home_sidebar' => '',
                'mod_detail_top' => '',
                'mod_detail_sidebar' => '',
                'mod_detail_inline' => '',
            ]), 'type' => 'json', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['group' => $setting['group'], 'key' => $setting['key']],
                $setting
            );
        }

        Cache::forget('app.settings');
    }

    public function down(): void
    {
        DB::table('settings')->where('group', 'ads')->whereIn('key', [
            'enabled', 'test_mode', 'client_id', 'label_text', 'slots'
        ])->delete();

        Cache::forget('app.settings');
    }
};
