<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $table = 'orders';

  protected $fillable = ['order_number', 'sub_total', 'user_id'];

  public function items() {
    return $this->hasMany(OrderItem::class, 'order_id');
  }

  public function user() {
    return $this->belongsTo(User::class);
  }
}
