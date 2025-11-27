<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cart::create([
          'book_id' => 1,
          'user_id' => 1,
          'quantity' => 2,
          'total_price' => 50000
        ]);

        Cart::create([
          'book_id' => 1,
          'user_id' => 2,
          'quantity' => 4,
          'total_price' => 100000
        ]);
    }
}
