<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('role')->get();
            return response()->json($users); 
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching users', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
{
    try {
        $user = User::with('role')->findOrFail($id); // Trae los datos del usuario y su rol cambio para poder ver los roles en los usuarios de la api 
        return response()->json($user);
    } catch (\Exception $e) {
        Log::error('Error fetching user: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
        return response()->json(['message' => 'An error occurred while fetching the user', 'error' => $e->getMessage()], 500);
    }
}

    
    public function store(Request $request)
{
    try {
        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'nullable|string|email|max:120|unique:user',
            'password' => 'required|string',
            'phone' => 'nullable|string|size:10',
            'location_type' => 'required|string|max:20',
            'status' => 'required|string|max:10',
            'store_id' => 'nullable|exists:store,store_id',
            'role_id' => 'required|exists:roles,role_id', // Valida que el role_id exista en la tabla roles
            'firebase_user_ID' => 'required|string|max:30',
        ]);

        // Crea el usuario con los datos validados
        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    } catch (\Exception $e) {
        Log::error('Error creating user: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
        return response()->json(['message' => 'An error occurred while creating the user', 'error' => $e->getMessage()], 500);
    }
}

public function update(Request $request, $id)
{
    try {
        $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'first_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
               // 'username' => 'required|string|max:30|unique:user,username,' . $id . ',user_id',
                'email' => 'nullable|string|email|max:120|unique:user,email,' . $id . ',user_id',
                'password' => 'nullable|string',
                'phone' => 'nullable|string|size:10',
                'role' => 'required|string|max:30',
                'location_type' => 'required|string|max:20',
                'status' => 'required|string|max:10',
                'store_id' => 'nullable|exists:store,store_id'
            ]);

        // Actualiza los datos del usuario
        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
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
    public function getUserByFirebaseID($FBID)
    {
        try {
            // Obtener el usuario por el ID de Firebase
            $user = User::where('firebase_user_ID', $FBID)->with('role')->first();
    
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            // Preparar la respuesta incluyendo el rol completo
            $transformedUser = [
                "name" => $user->first_name . ' ' . $user->last_name,
                "email" => $user->email,
                "phone" => $user->phone,
                "role" => $user->role ? [
                "name" => $user->role->name,] : null,
                "locationType" => $user->location_type,
                "status" => $user->status,
                "FBID" => $user->firebase_user_ID
            ];
    
            return response()->json($transformedUser);
        } catch (\Exception $e) {
            Log::error('Error fetching user by Firebase ID: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the user', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateRole(Request $request, $userId)
{
  
    $validatedData = $request->validate([
        'role_id' => 'required|exists:roles,role_id',
    ]);

    try {
        $user = User::findOrFail($userId);

        
        $user->role_id = $validatedData['role_id'];
        $user->save();

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        Log::error('Error al actualizar el rol del usuario: ' . $e->getMessage());
        return response()->json([
            'message' => 'Hubo un error al actualizar el rol',
            'error' => $e->getMessage(),
        ], 500);
    }
}
 
    
}


