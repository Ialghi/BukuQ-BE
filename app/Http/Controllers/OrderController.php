<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\String_;

class OrderController extends Controller
{
  public function index()
  {
    $orders = Order::with(['items.book', 'user'])->get();

    if ($orders->isEmpty()) {
      return response()->json([
        'success' =>  true,
        'message' => 'Data is empty'
      ], 200);
    }

    return response()->json([
      'success' => true,
      'message' => 'Get all orders data',
      'data' => $orders
    ], 200);
  }

  public function show(string $id)
  {
    $order = Order::with(['items.book'])->find($id);

    if (!$order) {
      return response()->json([
        'success' => false,
        'message' => 'Data not found'
      ], 404);
    }

    return response()->json([
      'success' => true,
      'message' => 'Get detailed order data',
      'data' => $order
    ], 200);
  }

  public function userOrder()
  {
    $user = auth('api')->user();

    if (!$user) {
      return response()->json([
        'success' => false,
        'message' => 'Unautorized'
      ], 401);
    }

    $order = Order::with(['items.book', 'items.book.author'])->where('user_id', $user->id)->get();

    if ($order->isEmpty()) {
      return response()->json([
        'success' => true,
        'message' => 'your order is empty'
      ], 200);
    }

    return response()->json([
      'success' => true,
      'message' => "Get user {$user->name} order data",
      'data' => $order
    ], 200);
  }

  public function store()
  {
    $user = auth('api')->user();

    if (!$user) {
      return response()->json([
        'success' => false,
        'message' => 'Unautorized'
      ], 401);
    }

    $cart = Cart::where('user_id', $user->id)->get();

    $unique_code = 'ORD-' . strtoupper(uniqid());

    $subTotal = $cart->sum('total_price');

    if ($cart->isEmpty()) {
      return response()->json([
        'success' => false,
        'message' => 'Your cart is empty',
      ], 500);
    }

    $order = Order::create([
      'order_number' => $unique_code,
      'user_id' => $user->id,
      'sub_total' => $subTotal,
    ]);

    foreach ($cart as $item) {
      $book = Book::find($item->book_id);

      if ($book->stock < $item->quantity) {
        return response()->json([
          'success' => false,
          'message' => "Stock not enough for {$book->title}"
        ], 400);
      }

      $book->stock -= $item->quantity;
      $book->save();

      OrderItem::create([
        'order_id' => $order->id,
        'book_id' => $item->book_id,
        'quantity' => $item->quantity,
        'total_price' => $item->total_price,
      ]);
    }

    Cart::where('user_id', $user->id)->delete();

    return response()->json([
      'success' => true,
      'message' => 'You have successfully placed your order',
      'data' => $order
    ], 200);
  }

  public function update(Request $request, string $id)
  {
    $order = Order::find($id);

    if (!$order) {
      return response()->json([
        'success' => false,
        'message' => 'Data not found'
      ], 404);
    }

    $validator = Validator($request->all(), [
      'status' => 'required|string|in:pending,success,cancel',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => $validator->errors()
      ], 422);
    }

    $order->update([
      'status' => $request->status,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Order status updated successfully',
      'data' => $order
    ], 200);
  }

  public function destroy(string $id)
  {
    $order = Order::find($id);

    if (!$order) {
      return response()->json([
        'success' => false,
        'message' => 'Data not found'
      ], 404);
    }

    $order->delete();

    return response()->json([
      'success' => true,
      'message' => 'Order data deleted successfully'
    ], 200);
  }
}
