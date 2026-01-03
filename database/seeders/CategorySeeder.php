<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Trucks',
                'description' => 'Truck mods for ETS2 and ATS',
                'icon' => 'truck',
                'order' => 1,
            ],
            [
                'name' => 'Trailers',
                'description' => 'Trailer mods and cargo additions',
                'icon' => 'trailer',
                'order' => 2,
            ],
            [
                'name' => 'Maps',
                'description' => 'Map expansions and new locations',
                'icon' => 'map',
                'order' => 3,
            ],
            [
                'name' => 'Skins',
                'description' => 'Paint jobs and visual customizations',
                'icon' => 'palette',
                'order' => 4,
            ],
            [
                'name' => 'Sound Mods',
                'description' => 'Engine sounds and audio improvements',
                'icon' => 'volume',
                'order' => 5,
            ],
            [
                'name' => 'Other',
                'description' => 'Miscellaneous mods and utilities',
                'icon' => 'package',
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'order' => $category['order'],
                'is_active' => true,
            ]);
        }
    }
}
