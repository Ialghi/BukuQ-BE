<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Harry Potter and the Sorcerers Stone',
            'desc' => 'The first book in the Harry Potter series.',
            'price' => 25000.00,
            'stock' => 30,
            'book_cover' => 'https://res.cloudinary.com/dkpanabg7/image/upload/v1764079620/harry_potter_g1djkd.jpg',
            'cloudinary_public_id' => 'harry_potter_g1djkd',
            'genre_id' => 1,
            'author_id' => 1
        ]);

        Book::create([
            'title' => 'The Lord of the Rings',
            'desc' => 'A classic fantasy novel by J.R.R. Tolkien.',
            'price' => 60000.00,
            'stock' => 30,
            'book_cover' => 'https://res.cloudinary.com/dkpanabg7/image/upload/v1764211818/the_lord_of_the_rings_sdmymr.jpg',
            'cloudinary_public_id' => 'the_lord_of_the_rings_sdmymr',
            'genre_id' => 1,
            'author_id' => 2
        ]);

        Book::create([
            'title' => '1984',
            'desc' => 'A dystopian novel by George Orwell.',
            'price' => 40000.00,
            'stock' => 40,
            'book_cover' => 'https://res.cloudinary.com/dkpanabg7/image/upload/v1764211940/1984_j8xtqw.jpg',
            'cloudinary_public_id' => '1984_j8xtqw',
            'genre_id' => 2,
            'author_id' => 3
        ]);
    }
}
