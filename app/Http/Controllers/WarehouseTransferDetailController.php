<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransferDetail;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class WarehouseTransferDetailController extends Controller
{
    public function index()
    {
        return WarehouseTransferDetail::with(['transfer', 'product'])->get();
    }

    public function store(Request $request)
{
    DB::beginTransaction(); // Start a transaction

    try {
        // Validate the request data
        $validatedData = $request->validate([
            'transfer_id' => 'required|exists:warehouse_transfer,transfer_id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:product,product_id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $transferId = $validatedData['transfer_id'];
        $productDetails = $validatedData['products'];

        // Loop through each product to validate and update inventory
        $transferDetails = [];
        foreach ($productDetails as $product) {
            $inventory = Inventory::where('product_id', $product['product_id'])
            ->whereNull('store_id') // Check if store_id is NULL
            ->first();
        

            if (!$inventory) {
                return response()->json([
                    'error' => "Inventory record not found for product ID {$product['product_id']}",
                ], 404);
            }

            // Check if there's enough stock
            if ($inventory->stock < $product['quantity']) {
                return response()->json(['error' => 'Insufficient stock'], 400);
            }

            // Update stock and reserved stock
            $inventory->stock -= $product['quantity'];
            $inventory->Reserved_Stock += $product['quantity'];
            $inventory->save();

            // Prepare data for transfer details
            $transferDetails[] = [
                'transfer_id' => $transferId,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert transfer details
        WarehouseTransferDetail::insert($transferDetails);

        DB::commit(); // Commit the transaction

        return response()->json(['message' => 'Products transferred successfully'], 201);
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback the transaction in case of error
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function show($id)
    {
        return WarehouseTransferDetail::with(['transfer', 'product'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $transferDetail = WarehouseTransferDetail::findOrFail($id);

        $validatedData = $request->validate([
            'transfer_id' => 'required|exists:warehouse_transfer,transfer_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer'
        ]);

        $transferDetail->update($validatedData);

        return response()->json(['message' => 'Warehouse transfer detail updated successfully', 'data' => $transferDetail]);
    }

    public function destroy($id)
    {
        $transferDetail = WarehouseTransferDetail::findOrFail($id);
        $transferDetail->delete();

        return response()->json(['message' => 'Warehouse transfer detail deleted successfully']);
    }
}
