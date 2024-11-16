<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        return Product::all();
    }

public function store(Request $request)
{
    \Log::info('Datos recibidos:', $request->all());

    $validatedData = $request->validate([
        'name' => 'required|string|max:80',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'status' => 'nullable|string|max:10',
        'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'required|exists:category,category_id',
        'supplier_id' => 'required|exists:supplier,supplier_id',
        'type' => 'required|string|max:50',
    ]);

    // Generar SKU automáticamente si no está en los datos validados
    $validatedData['sku'] = $request->input('sku', strtoupper(\Str::random(10)));

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $validatedData['image'] = 'storage/' . $path;
    }

    try {
        $product = Product::create($validatedData);
        return response()->json($product, 201);
    } catch (\Exception $e) {
        \Log::error("Error al guardar el producto: " . $e->getMessage());
        return response()->json(['message' => 'Error guardando el producto'], 500);
    }
}


public function destroy($id)
{
    try {
        $product = Product::findOrFail($id);

        // Verificar y eliminar la imagen si existe
        if ($product->image && Storage::exists('public/' . str_replace('storage/', '', $product->image))) {
            Storage::delete('public/' . str_replace('storage/', '', $product->image));
        }

        // Eliminar el producto de la base de datos
        $product->delete();

        return response()->json(['message' => 'Product and associated image deleted successfully'], 200);
    } catch (\Exception $e) {
        \Log::error("Error deleting product: " . $e->getMessage());
        return response()->json(['message' => 'Error deleting product'], 500);
    }
}


    // Mostrar un producto específico
    public function show($id)
    {
        return Product::findOrFail($id);
    }

public function update(Request $request, $id)
{
    \Log::info('Datos recibidos en update:', $request->all());

    $product = Product::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'nullable|string|max:80',
        'sku' => 'nullable|string|max:10|unique:product,sku,' . $id . ',product_id',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric',
        'status' => 'nullable|string|max:10',
        'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'nullable|exists:category,category_id',
        'supplier_id' => 'nullable|exists:supplier,supplier_id',
        'type' => 'nullable|string|max:50',
    ]);

    // Manejo del campo de imagen
    if ($request->hasFile('image')) {
        // Eliminar imagen anterior si existe
        if ($product->image && Storage::exists('public/' . str_replace('storage/', '', $product->image))) {
            Storage::delete('public/' . str_replace('storage/', '', $product->image));
        }
        // Guardar nueva imagen
        $path = $request->file('image')->store('images', 'public');
        $product->image = 'storage/' . $path;
    } elseif ($request->has('image') && $request->input('image') === '') {
        // Eliminar imagen si se envía una cadena vacía
        if ($product->image && Storage::exists('public/' . str_replace('storage/', '', $product->image))) {
            Storage::delete('public/' . str_replace('storage/', '', $product->image));
        }
        $product->image = null;
    }

    // Actualizar otros campos
    $product->fill($validatedData);
    $product->save();

    return response()->json($product, 200);
}




    public function getProductWithCategories($sku)
    {
        try {
            $product = Product::where('sku', $sku)->first();

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $subCategory = Category::find($product->category_id);
            $parentCategory = $subCategory ? Category::find($subCategory->parent_id) : null;

            return response()->json([
                'product' => $product,
                'sub_category_name' => $subCategory ? $subCategory->name : null,
                'parent_category_name' => $parentCategory ? $parentCategory->name : null,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error fetching product with categories: " . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}