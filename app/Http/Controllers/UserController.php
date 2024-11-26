<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            return User::all();
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching users', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
                'email' => 'nullable|string|email|max:120|unique:user',
                'password' => 'required|string',
                'phone' => 'nullable|string|size:10',
                'role' => 'required|string|max:30',
                'location_type' => 'required|string|max:20',
                'status' => 'required|string|max:10',
                'store_id' => 'nullable|exists:store,store_id',
                'firebase_user_ID' => 'required|string|max:30'
            ]);

            $user = User::create($validatedData);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the user', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            return User::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the user', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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

            return response()->json(['message' => 'User updated successfully', 'user' => $user]);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the user', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the user', 'error' => $e->getMessage()], 500);
        }
    }

    //mobile
    public function getUserByFirebaseID($FBID) {
        try {
            $user = User::where('firebase_user_ID', $FBID)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            $transformedUser = [
                "name" => $user->first_name . ' ' . $user->last_name,
                "email" => $user->email,
                "phone" => $user->phone,
                "role" => $user->role,
                "locationType" => $user->location_type,
                "status" => $user->status,
                "FBID" => $user->firebase_user_ID
            ];
    
            return response()->json($transformedUser);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the user', 'error' => $e->getMessage()], 500);
        }
    }
    
}
