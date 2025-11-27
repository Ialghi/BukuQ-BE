<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\OrderController;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::apiResource('/books', BookController::class)->only('show', 'index');
Route::apiResource('/authors', AuthorController::class)->only('show', 'index');
Route::apiResource('/genres', GenreController::class)->only('show', 'index');
Route::post('/messages', [ContactController::class, 'store']);


Route::middleware(['auth:api'])->group(function () {
  Route::apiResource('/cart', CartController::class);

  // order controller
  Route::post('/checkout', [OrderController::class, 'store']);
  Route::get("/myorders",[OrderController::class, "userOrder"]);
  Route::get("/orders/{id}", [OrderController::class, "show"]);

  // admin route
  Route::middleware(['role:admin'])->group(function () {
    Route::apiResource('/authors', AuthorController::class)->only('update', 'store', 'destroy');
    Route::apiResource('/books', BookController::class)->only('update', 'store', 'destroy');
    Route::apiResource('/genres', GenreController::class)->only('update', 'store', 'destroy');
    Route::apiResource('/orders', OrderController::class)->only('index', 'update', 'destroy');
    Route::apiResource('/messages', ContactController::class)->only('destroy', 'index');
    Route::apiResource('/users', AuthController::class);
  });
});
