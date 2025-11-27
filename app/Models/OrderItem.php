<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  protected $table = 'order_items';

  protected $fillable = ['book_id', 'order_id', 'quantity', 'total_price'];

  public function order() {
    return $this->belongsTo(Order::class, 'order_id');
  }

  public function book() {
    return $this->belongsTo(Book::class);
  }

  public function author() {
    return $this->belongsTo(Author::class);
  }
}
