<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(){
        $authors = Author::all();

        if ($authors->isEmpty()) {
            return response()->json([
                'success' =>  true,
                'message' => 'Data is empty'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get all authors data',
            'data' => $authors
        ], 200);
    }

    public function store(Request $request) {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:100',
            'about' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $author = Author::create([
            'name' => $request->name,
            'about' => $request->about
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Author data added successfully',
            'data' => $author
        ], 201);
    }

    public function show(string $id) {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get detailed data of author',
            'data' => $author
        ], 200);
    }

    public function update(string $id, Request $request) {
        $author  = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $validator = Validator($request->all(), [
            'name' => 'required|string|max:100',
            'about' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'about' => $request->about
        ];

        $author->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Author data updated successfully',
            'data' => $author
        ], 200);
    }

    public function destroy(string $id) {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author data deleted successfully'
        ], 200);
    }
}
