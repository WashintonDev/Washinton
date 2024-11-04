<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        return Category::all();
    }

    // Crear una nueva categoría
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:category,category_id',
        ]);

        return Category::create($validatedData);
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        return Category::findOrFail($id);
    }

    // Actualizar una categoría existente
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:category,category_id',
        ]);

        $category->update($validatedData);

        return $category;
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
