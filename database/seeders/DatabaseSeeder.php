<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. On crée d'abord les tags
        $this->call(TagSeeder::class);

        // 2. On crée une grande liste d'albums
        $albums = [
            ['name' => 'good kid, m.A.A.d city', 'artist' => 'Kendrick Lamar', 'year' => '2012'],
            ['name' => 'JEFFERY', 'artist' => 'Young Thug', 'year' => '2016'],
            ['name' => 'The Forever Story', 'artist' => 'JID', 'year' => '2022'],
            ['name' => 'SOS', 'artist' => 'SZA', 'year' => '2022'],
            ['name' => 'Cross', 'artist' => 'Justice', 'year' => '2007'],
            ['name' => 'Dynamite', 'artist' => 'Jamiroquai', 'year' => '2005'],
            ['name' => 'Ipséité', 'artist' => 'Damso', 'year' => '2017'],
            ['name' => 'Feu', 'artist' => 'Nekfeu', 'year' => '2015'],
            ['name' => 'Random Access Memories', 'artist' => 'Daft Punk', 'year' => '2013'],
            ['name' => 'ASTROWORLD', 'artist' => 'Travis Scott', 'year' => '2018'],
            ['name' => 'Blonde', 'artist' => 'Frank Ocean', 'year' => '2016'],
            ['name' => 'IGOR', 'artist' => 'Tyler, The Creator', 'year' => '2019'],
            ['name' => 'Starboy', 'artist' => 'The Weeknd', 'year' => '2016'],
            ['name' => 'Currents', 'artist' => 'Tame Impala', 'year' => '2015'],
            ['name' => 'After Hours', 'artist' => 'The Weeknd', 'year' => '2020'],
            ['name' => '2001', 'artist' => 'Dr. Dre', 'year' => '1999'],
            ['name' => 'DAMN.', 'artist' => 'Kendrick Lamar', 'year' => '2017'],
            ['name' => 'Graduation', 'artist' => 'Kanye West', 'year' => '2007'],
            ['name' => 'Norman Fucking Rockwell!', 'artist' => 'Lana Del Rey', 'year' => '2019'],
            ['name' => 'Discovery', 'artist' => 'Daft Punk', 'year' => '2001']
        ];

        $tags = \App\Models\Tag::all();

        foreach ($albums as $albumData) {
            $cover = $this->getCoverFromDeezer($albumData['name'], $albumData['artist']);
            
            $album = \App\Models\Album::create([
                'name' => $albumData['name'],
                'artist' => $albumData['artist'],
                'cover' => $cover,
                'year' => $albumData['year']
            ]);

            // Attacher 1 à 3 tags aléatoires à cet album
            $randomTags = $tags->random(rand(1, 3))->pluck('id');
            $album->tags()->attach($randomTags);
        }
    }

    private function getCoverFromDeezer($albumName, $artistName)
    {
        $response = Http::withoutVerifying()->get('https://api.deezer.com/search/album', [
            'q' => $artistName . ' ' . $albumName,
            'limit' => 1
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'] ?? [];
            if (!empty($data)) {
                return $data[0]['cover_medium'] ?? 'https://via.placeholder.com/500';
            }
        }

        return 'https://via.placeholder.com/500';
    }
}
