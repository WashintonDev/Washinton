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
}
