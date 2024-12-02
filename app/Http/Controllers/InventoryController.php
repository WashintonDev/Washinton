<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
public function index()
{
    // Cargar inventarios con sus relaciones
    $inventories = Inventory::with(['product.productImages', 'warehouse', 'store'])->get();

    // Transformar la respuesta para incluir la primera imagen del producto
    $transformedInventories = $inventories->map(function ($inventory) {
        $product = $inventory->product;

        // Obtener la primera imagen del producto, si existe
        $firstImage = $product && $product->productImages->isNotEmpty()
            ? $product->productImages[0]->image_path
            : null;

        return [
            'inventory_id' => $inventory->inventory_id,
            'product_id' => $inventory->product_id,
            'warehouse_id' => $inventory->warehouse_id,
            'store_id' => $inventory->store_id,
            'stock' => $inventory->stock,
            'created_at' => $inventory->created_at,
            'updated_at' => $inventory->updated_at,
            'Reserved_Stock' => $inventory->Reserved_Stock ?? 0,
            'product' => $product ? array_merge($product->toArray(), ['first_image' => $firstImage]) : null,
            'warehouse' => $inventory->warehouse,
            'store' => $inventory->store,
        ];
    });

    return response()->json($transformedInventories);
}


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'warehouse_id' => 'nullable|exists:warehouse,warehouse_id',
            'store_id' => 'nullable|exists:store,store_id',
            'stock' => 'required|integer'
        ]);

        return Inventory::create($validatedData);
    }

    public function show($id)
    {
        return Inventory::with(['product', 'warehouse', 'store'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'warehouse_id' => 'nullable|exists:warehouse,warehouse_id',
            'store_id' => 'nullable|exists:store,store_id',
            'stock' => 'required|integer'
        ]);

        $inventory->update($validatedData);

        return $inventory;
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return response()->json(['message' => 'Inventory deleted successfully']);
    }
}
