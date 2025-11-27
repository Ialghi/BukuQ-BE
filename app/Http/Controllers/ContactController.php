<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
  public function index() {
    $contacts = Contact::all();

    if ($contacts->isEmpty()) {
      return response()->json([
        'success' =>  true,
        'message' => 'Data is empty'
      ], 200);
    }

    return response()->json([
      'success' => true,
      'message' => 'Get all books data',
      'data' => $contacts
    ], 200);
  }

  public function store(Request $request) {
        $validator = Validator($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'message sent successfully',
            'data' => $contact
        ], 201);
    }

    public function destroy(string $id) {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'message deleted successfully'
        ], 200);
    }

}
