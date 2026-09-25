<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Mélancolique',  'color' => '#5B7DB1', 'emoji' => ''],
            ['name' => 'Joyeux',        'color' => '#F5A623', 'emoji' => ''],
            ['name' => 'Chill',         'color' => '#7EC8B0', 'emoji' => ''],
            ['name' => 'Soirée',        'color' => '#9B59B6', 'emoji' => ''],
            ['name' => 'Énergique',     'color' => '#E74C3C', 'emoji' => ''],
            ['name' => 'Romantique',    'color' => '#E87FA0', 'emoji' => ''],
            ['name' => 'Nocturne',      'color' => '#34495E', 'emoji' => ''],
            ['name' => 'Road Trip',     'color' => '#E67E22', 'emoji' => ''],
            ['name' => 'Focus',         'color' => '#3498DB', 'emoji' => ''],
            ['name' => 'Nostalgique',   'color' => '#8E7CC3', 'emoji' => ''],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
