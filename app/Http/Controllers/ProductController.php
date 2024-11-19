<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Inventory;

class ProductController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        return Product::all();
    }

    private function generateNumericSku()
    {
        do {
            $sku = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Product::where('sku', $sku)->exists());
    
        return $sku;
    }


    // Crear un nuevo producto
    public function store(Request $request)
    {
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'brand' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'nullable|string|max:10',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'category_id' => 'required|exists:category,category_id',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'type' => 'required|string|max:50', // Asegura que type sea obligatorio
            'volume' => 'nullable|numeric',
            'unit' => 'nullable|string|max:10',
        ]);
    
        // Generar SKU automáticamente
        $validatedData['sku'] = $this->generateNumericSku();
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validatedData['image'] = 'storage/' . $path;
        }
    
        try {
            // Crear el producto
            $product = Product::create($validatedData);
    
            // Crear la relación en product_supplier
            $product->suppliers()->attach($validatedData['supplier_id']);
    
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error guardando el producto'], 500);
        }
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
            'name' => 'nullable|string|max:80',
            'sku' => 'nullable|string|max:10|unique:product,sku,' . $id . ',product_id',
            'brand' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'status' => 'nullable|string|max:10',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'category_id' => 'nullable|exists:category,category_id',
            'supplier_id' => 'nullable|exists:supplier,supplier_id',
            'type' => 'nullable|string|max:50',
            'volume' => 'nullable|numeric',
            'unit' => 'nullable|string|max:10',
        ]);
    
        // Manejo del campo de imagen
        if ($request->hasFile('image')) {
            if ($product->image && Storage::exists('public/' . str_replace('storage/', '', $product->image))) {
                Storage::delete('public/' . str_replace('storage/', '', $product->image));
            }
            $path = $request->file('image')->store('images', 'public');
            $product->image = 'storage/' . $path;
        } elseif ($request->has('image') && $request->input('image') === '') {
            if ($product->image && Storage::exists('public/' . str_replace('storage/', '', $product->image))) {
                Storage::delete('public/' . str_replace('storage/', '', $product->image));
            }
            $product->image = null;
        }
    
        $product->fill($validatedData);
        $product->save();
    
        // Actualizar la relación en product_supplier
        if (isset($validatedData['supplier_id'])) {
            $product->suppliers()->sync([$validatedData['supplier_id']]);
        }
    
        return response()->json($product, 200);
    }

    // Eliminar un producto
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
            return response()->json(['message' => 'Error deleting product'], 500);
        }
    }

    //This function has to only retrieve the product that have stock avaliable, also it has to return the stock amount they have
    public function product_names()
    {
        $products = Product::all();
        $inventory = Inventory::whereNotNull('warehouse_id')->get();
    
        if ($products->isNotEmpty()) {
            // Map through products to include the stock from inventory
            $transformedProducts = $products->map(function ($product) use ($inventory) {
                // Find inventory stock for the current product
                $productStock = $inventory->where('product_id', $product->product_id)->sum('stock');
    
                return [
                    'label' => $product->name,
                    'value' => $product->product_id,
                    'stock' => $productStock // Add the stock of the product
                ];
            });
    
            return response()->json($transformedProducts);
        } else {
            return response()->json(['message' => 'No products available']);
        }
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
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function getProductNames()
    {

            // Retrieve an array with only the 'name' column
            $productNames = Product::pluck('name');  // This will return an array of product names
    
            // Return the array as a JSON response
            return response()->json($productNames);
        }
}
