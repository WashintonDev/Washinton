<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        return Product::all();
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'sku' => 'required|string|max:10|unique:product',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'nullable|string|max:10',
            'image' => 'nullable|string|max:100',
            'category_id' => 'required|exists:category,category_id',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'type' => 'required|string|max:50',
        ]);

        return Product::create($validatedData);
    }

    // Mostrar un producto específico
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    // Actualizar un producto existente
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'sku' => 'required|string|max:10|unique:product,sku,' . $id . ',product_id',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'nullable|string|max:10',
            'image' => 'nullable|string|max:100',
            'category_id' => 'required|exists:category,category_id',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'type' => 'required|string|max:50',
        ]);

        $product->update($validatedData);

        return $product;
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
