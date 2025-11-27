<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Genre::create([
            'name' => 'Fiksi',
            'desc' => 'Fiksi adalah genre cerita rekaan yang bersifat imajinatif dan tidak berdasarkan kejadian nyata.'
        ]);

        Genre::create([
            'name' => 'Non-Fiksi',
            'desc' => 'Non-fiksi adalah genre tulisan yang berdasarkan fakta, kejadian nyata, dan informasi yang dapat diverifikasi.'
        ]);

        Genre::create([
            'name' => 'Fiksi Sains',
            'desc' => 'Fiksi sains adalah genre fiksi yang mengangkat tema ilmiah atau teknologi, sering berlatar masa depan atau luar angkasa.'
        ]);
    }
}
