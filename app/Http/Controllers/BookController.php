<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Cloudinary;

use function Symfony\Component\String\s;

class BookController extends Controller
{
  public function index()
  {
    $books = Book::with('genre', 'author')->get();

    if ($books->isEmpty()) {
      return response()->json([
        'success' =>  true,
        'message' => 'Data is empty'
      ], 200);
    }

    return response()->json([
      'success' => true,
      'message' => 'Get all books data',
      'data' => $books
    ], 200);
  }

  public function store(Request $request)
  {
    $validator = Validator($request->all(), [
      'title' => 'required|string|max:255',
      'desc' => 'required|string',
      'price' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'book_cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      'genre_id' => 'required|exists:genres,id',
      'author_id' => 'required|exists:authors,id'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => $validator->errors()
      ], 422);
    }

    $image = $request->file('book_cover');
    $imageUpload = Storage::disk('cloudinary')->put('', $image);

    $imageUrl = 'https://res.cloudinary.com/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload/v' . time() . '/' . $imageUpload . '.jpg';

    $book = Book::create([
      'title' => $request->title,
      'desc' => $request->desc,
      'price' => $request->price,
      'stock' => $request->stock,
      'book_cover' => $imageUrl,
      'cloudinary_public_id' => $imageUpload,
      'genre_id' => $request->genre_id,
      'author_id' => $request->author_id
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Book data added successfully',
      'data' => $book
    ], 201);
  }

  public function show(string $id)
  {
    $book = Book::with('genre', 'author')->find($id);

    if (!$book) {
      return response()->json([
        'success' => false,
        'message' => 'Data not found'
      ], 404);
    }

    return response()->json([
      'success' => true,
      'message' => 'Get detailed data of book',
      'data' => $book
    ], 200);
  }

  public function update(string $id, Request $request)
  {
    $book  = Book::find($id);

    if (!$book) {
      return response()->json([
        'success' => false,
        'message' => 'Data not found'
      ], 404);
    }

    $validator = Validator($request->all(), [
      'title' => 'required|string|max:255',
      'desc' => 'required|string',
      'price' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'book_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'genre_id' => 'required|exists:genres,id',
      'author_id' => 'required|exists:authors,id'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => $validator->errors()
      ], 422);
    }

    $data = [
      'title' => $request->title,
      'desc' => $request->desc,
      'price' => $request->price,
      'stock' => $request->stock,
      'genre_id' => $request->genre_id,
      'author_id' => $request->author_id
    ];

    if ($request->hasFile('book_cover')) {

      if ($book->cloudinary_public_id) {
        Storage::disk('cloudinary')->delete($book->cloudinary_public_id);
      }

      $image = $request->file('book_cover');

      $imageUpload = Storage::disk('cloudinary')->put('', $image);
      $data['book_cover'] = 'https://res.cloudinary.com/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload/v' . time() . '/' . $imageUpload . '.jpg';
      $data['cloudinary_public_id'] = $imageUpload;
    }

    $book->update($data);

    return response()->json([
      'success' => true,
      'message' => 'Book data updated successfully',
      'data' => $book
    ], 200);
  }

  public function destroy(string $id)
  {
    $book = Book::find($id);

    if (!$book) {
      return response()->json([
        'success' => false,
        'message' => 'Data not found'
      ], 404);
    }

    if ($book->cloudinary_public_id) {
        Storage::disk('cloudinary')->delete($book->cloudinary_public_id);
      }

    $book->delete();

    return response()->json([
      'success' => true,
      'message' => 'Book data deleted successfully'
    ], 200);
  }
}
