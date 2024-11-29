<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Inventory;
use App\Models\Supplier;

class ProductController extends Controller
{
    // Listar todos los productos
public function index()
{
    return Product::with('productImages')->get();
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
            'additional_images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,avif|max:2048', // Para imágenes adicionales
            'category_id' => 'required|exists:category,category_id',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'type' => 'required|string|max:50',
            'volume' => 'nullable|numeric',
            'unit' => 'nullable|string|max:10',
        ]);
    
        $validatedData['sku'] = $this->generateNumericSku();
        
        try {
            $product = Product::create($validatedData);
    
            // Guardar imágenes adicionales
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    $path = $image->store('product_images', 'public');
                    $product->productImages()->create(['image_path' => 'storage/' . $path]);
                }
            }
    
            return response()->json($product->load('productImages'), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error guardando el producto', 'error' => $e->getMessage()], 500);
        }
    }
    

    // Mostrar un producto específico
    public function show($id)
    {
        $product = Product::with('productImages')->findOrFail($id);
        return response()->json($product);
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
            'additional_images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'category_id' => 'nullable|exists:category,category_id',
            'supplier_id' => 'nullable|exists:supplier,supplier_id',
            'type' => 'nullable|string|max:50',
            'volume' => 'nullable|numeric',
            'unit' => 'nullable|string|max:10',
        ]);
    
        if ($request->hasFile('image')) {
            if ($product->image && Storage::exists('public/' . str_replace('storage/', '', $product->image))) {
                Storage::delete('public/' . str_replace('storage/', '', $product->image));
            }
            $path = $request->file('image')->store('images', 'public');
            $product->image = 'storage/' . $path;
        }
    
        // Actualizar imágenes adicionales
        if ($request->hasFile('additional_images')) {
            // Eliminar las imágenes existentes
            foreach ($product->productImages as $image) {
                if (Storage::exists('public/' . str_replace('storage/', '', $image->image_path))) {
                    Storage::delete('public/' . str_replace('storage/', '', $image->image_path));
                }
                $image->delete();
            }
    
            // Agregar las nuevas imágenes
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->productImages()->create(['image_path' => 'storage/' . $path]);
            }
        }
    
        $product->fill($validatedData);
        $product->save();
    
        return response()->json($product->load('productImages'), 200);
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
                    'stock' => $productStock, // Add the stock of the product
                    'price' => $product->price //added price because trejo wanted to show the value of
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
            $product = Product::where('sku', $sku)->with('productImages')->first();
    
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
    
            $category = Category::find($product->category_id);
            $supplier = Supplier::find($product->supplier_id);
    
            $transformedProduct = [
                "product_id" => $product->product_id,
                "name" => $product->name,
                "sku" => $product->sku,
                "brand" => $product->brand,
                "description" => $product->description,
                "price" => $product->price,
                "status" => $product->status,
                "category" => $category ? $category->name : null,
                "supplier" => $supplier ? $supplier->name : null,
                "type" => $product->type,
                "volume" => $product->volume,
                "unit" => $product->unit,
                "image" => $product->productImages[0]->image_path
            ];
    
            return response()->json($transformedProduct);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
    
    public function getProductNames()
    {

            // Retrieve an array with only the 'name' column
            $products = Product::all();

            $transformedProducts = $products->map(function ($product){
                    return [
                        "name" => $product->name,
                        "sku" => $product->sku
                    ];
            });
    
            // Return the array as a JSON response
            return response()->json($transformedProducts);
        }
}
