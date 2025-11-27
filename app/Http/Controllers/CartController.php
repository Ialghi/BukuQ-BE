<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(){
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unautorized'
            ], 401);
        }

        $cart = Cart::with('book')->where('user_id', $user->id)->get();

        if ($cart->isEmpty()) {
            return response()->json([
                'success' =>  true,
                'message' => 'Data is empty'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get all cart items data',
            'data' => $cart
        ], 200);
    }

    public function store(Request $request) {
        $validator = Validator($request->all(), [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unautorized'
            ], 401);
        }

        $book = Book::find($request->book_id);

        if ($book->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'not enough stock of books remaining stock ' . $book->stock,
            ], 400);
        }

        $total_price = $book->price * $request->quantity;

        $cart = Cart::with('book')->where('user_id', $user->id)
        ->where('book_id', $book->id)->first();

        if ($cart) {
          $cart->quantity = $request->quantity;
          $cart->total_price = $total_price;
          $cart->save();

          $message = 'Quantity updated successfully';
        } else {
          $cart = Cart::create([
            'book_id' => $request->book_id,
            'user_id' => $user->id,
            'quantity' => $request->quantity,
            'total_price' => $total_price
          ]);

          $message = 'Add to cart successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $cart
        ], 201);
    }

    public function destroy(string $id) {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart item deleted successfully'
        ], 200);
    }
}
