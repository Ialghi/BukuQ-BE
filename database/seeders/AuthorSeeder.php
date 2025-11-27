<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create([
            'name' => 'J.K. Rowling',
            'about' => 'J.K. Rowling adalah penulis Inggris, terkenal dengan seri Harry Potter.'
        ]);

        Author::create([
            'name' => 'George R.R. Martin',
            'about' => 'George R.R. Martin adalah penulis Amerika, pencipta seri Game of Thrones.'
        ]);

        Author::create([
            'name' => 'Isaac Asimov',
            'about' => 'Isaac Asimov adalah penulis Amerika, terkenal dengan karya fiksi ilmiah seperti seri Foundation dan Robot.'
        ]);
    }
}
