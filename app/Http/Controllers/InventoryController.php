<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return Inventory::with(['product', 'warehouse', 'store'])->get();
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
