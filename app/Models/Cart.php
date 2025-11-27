<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = ['book_id', 'user_id', 'quantity', 'total_price'];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
