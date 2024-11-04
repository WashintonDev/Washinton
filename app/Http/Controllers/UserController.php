<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'username' => 'required|string|max:30|unique:user',
            'email' => 'nullable|string|email|max:120|unique:user',
            'password' => 'required|string',
            'phone' => 'nullable|string|size:10',
            'role' => 'required|string|max:30',
            'location_type' => 'required|string|max:20',
            'status' => 'required|string|max:10',
            'store_id' => 'nullable|exists:store,store_id'
        ]);

        $user = User::create($validatedData);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'username' => 'required|string|max:30|unique:user,username,' . $id . ',user_id',
            'email' => 'nullable|string|email|max:120|unique:user,email,' . $id . ',user_id',
            'password' => 'nullable|string',
            'phone' => 'nullable|string|size:10',
            'role' => 'required|string|max:30',
            'location_type' => 'required|string|max:20',
            'status' => 'required|string|max:10',
            'store_id' => 'nullable|exists:store,store_id'
        ]);

        $user->update($validatedData);

        return $user;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
