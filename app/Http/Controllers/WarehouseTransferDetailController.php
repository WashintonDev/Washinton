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
                'product_id' => 'required|exists:product,product_id',
                'quantity' => 'required|integer|min:1'
            ]);
    
            $productId = $validatedData['product_id'];
            $quantity = $validatedData['quantity'];
    
            // Find the inventory record for the product and store/warehouse
            $inventory = Inventory::where('product_id', $productId)
                ->where('store_id', $request->input('store_id')) // Assuming store_id is in the request
                ->first();
    
            if (!$inventory) {
                return response()->json(['error' => 'Inventory record not found'], 404);
            }
    
            // Check if there's enough stock
            if ($inventory->stock < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 400);
            }
    
            // Update stock and reserved stock
            $inventory->stock -= $quantity;
            $inventory->Reserved_Stock += $quantity;
            $inventory->save();
    
            // Create the transfer detail
            $transferDetail = WarehouseTransferDetail::create($validatedData);
    
            DB::commit(); // Commit the transaction
    
            return response()->json($transferDetail, 201);
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
