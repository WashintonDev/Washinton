<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        try {
            return Role::all();
        } catch (\Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching roles', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:50|unique:roles',
                'permissions' => 'nullable|array', // Validar permisos como array
            ]);

            $validatedData['permissions'] = json_encode($validatedData['permissions']); // Convertir a JSON

            $role = Role::create($validatedData);
            return response()->json($role, 200);
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the role', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            return Role::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching role: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the role', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:50|unique:roles,name,' . $id . ',role_id',
                'permissions' => 'nullable|array',
            ]);

            $validatedData['permissions'] = json_encode($validatedData['permissions']);

            $role->update($validatedData);

            return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the role', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json(['message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the role', 'error' => $e->getMessage()], 500);
        }
    }

    /// implementar la asignación de roles a usuarios  --------- cambios de Rafael 


    public function assignRoleToUser(Request $request, $userId)
{
    try {
        $validatedData = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',  
        ]);

        // Buscar al usuario por su ID
        $user = User::findOrFail($userId);

        // Asignar el rol utilizando el role_id
        $user->role_id = $validatedData['role_id'];  // Asignar el role_id al usuario
        $user->save();  // Guardar cambios

        return response()->json(['message' => 'Rol asignado correctamente', 'user' => $user]);
    } catch (\Exception $e) {
        // Si ocurre un error, lo logueamos y respondemos con un mensaje de error
        Log::error('Error al asignar el rol al usuario: ' . $e->getMessage());
        return response()->json(['message' => 'Error interno del servidor', 'error' => $e->getMessage()], 500);
    }
}

//mobile
public function getUserByFirebaseID($FBID) {
    try {
        $user = User::where('firebase_user_ID', $FBID)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Cargar la relación 'role' para obtener los detalles del rol
        $role = $user->role ? $user->role->name : null; // Asegúrate de que 'role' tiene un nombre en la tabla 'roles'

        $transformedUser = [
            "name" => $user->first_name . ' ' . $user->last_name,
            "email" => $user->email,
            "phone" => $user->phone,
            "role" => $role, // Ahora incluye el nombre del rol
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


