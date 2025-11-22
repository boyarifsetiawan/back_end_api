<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $song = new Song();

            // KESALAHAN 1 & 3 DIPERBAIKI: Gunakan notasi -> dan double quotes
            $song->title = "Title $i";
            $song->author = "Author $i";
            $song->song_link = "http://example.com/song/$i";

            // KESALAHAN 2 DIPERBAIKI: Wajib memanggil save()
            $song->save();
        }
    }
}
