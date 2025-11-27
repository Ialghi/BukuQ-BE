<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
     public function index(){
        $genres = Genre::all();

        if ($genres->isEmpty()) {
            return response()->json([
                'success' =>  true,
                'message' => 'Data is Empty'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get all authors data',
            'data' => $genres
        ], 200);
    }

    public function store(Request $request) {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:100',
            'desc' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $genre = Genre::create([
            'name' => $request->name,
            'desc' => $request->desc
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Genre data added successfully',
            'data' => $genre
        ], 201);
    }

    public function show(string $id) {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get detailed genre data',
            'data' => $genre
        ], 200);
    }

    public function update(string $id, Request $request) {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $validator = Validator($request->all(), [
            'name' => 'required|string|max:100',
            'desc' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $genre->update([
          'name' => $request->name,
          'desc' => $request->desc
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Genre data updated successfully',
            'data' => $genre
        ], 200);
    }

    public function destroy(string $id) {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $genre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Genre data deleted successfully'
        ], 200);
    }
}
