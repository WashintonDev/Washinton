<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Listar imágenes de un producto.
     */
    public function index($product_id)
    {
        $product = Product::findOrFail($product_id);
        $images = $product->productImages;

        return response()->json($images, 200);
    }

    /**
     * Subir imágenes adicionales para un producto.
     */
    public function store(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        $request->validate([
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $uploadedImages = [];

            foreach ($request->file('images') as $image) {
                // Verificar si ya hay 5 imágenes
                if ($product->productImages->count() >= 5) {
                    return response()->json(['message' => 'No puedes subir más de 5 imágenes para este producto'], 400);
                }

                $path = $image->store('product_images', 'public');
                $imagePath = 'storage/' . $path;

                $productImage = $product->productImages()->create([
                    'image_path' => $imagePath,
                ]);

                $uploadedImages[] = $productImage;
            }

            return response()->json($uploadedImages, 201);
        }

        return response()->json(['message' => 'No se encontraron imágenes para subir'], 400);
    }

    /**
     * Eliminar una imagen adicional.
     */
    public function destroy($image_id)
    {
        $image = ProductImage::findOrFail($image_id);

        // Eliminar el archivo físico
        if (Storage::exists('public/' . str_replace('storage/', '', $image->image_path))) {
            Storage::delete('public/' . str_replace('storage/', '', $image->image_path));
        }

        // Eliminar el registro de la base de datos
        $image->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente'], 200);
    }
}
