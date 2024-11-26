<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransfer;
use Exception;
use Illuminate\Http\Request;
use  App\Models\Inventory;
use App\Models\WarehouseTransferDetail;
use Illuminate\Support\Facades\DB;

class WarehouseTransferController extends Controller
{
        public function index()
        {
            try {
                $transfers = WarehouseTransfer::with(['store', 'details.product'])->get();
    
                // Hide the created_at and updated_at fields and customize the response
                $transfers->each(function ($transfer) {
                    $transfer->makeHidden(['created_at', 'updated_at']);
                    $transfer->store->makeHidden(['created_at', 'updated_at', 'phone', 'address', 'status', 'city', 'state']);
                    foreach ($transfer->details as $detail) {
                        $detail->makeHidden(['created_at', 'updated_at']);
                        $detail->product->makeHidden(['created_at', 'updated_at', 'sku', 'brand', 'description', 'status', 'image', 'category_id', 'supplier_id', 'type', 'volume', 'unit']);
                    }
                });
    
                // Customize the response to include only the store name
                $response = $transfers->map(function ($transfer) {
                    $transferArray = $transfer->toArray();
                    $transferArray['store'] = $transfer->store->name;
                    $totalValue = $transfer->details->sum(function ($detail) { return $detail->product->price; });
                    $transferArray['totalValue'] = $totalValue;
                    return $transferArray;
                });
    
                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json(['message' => 'An error occurred while fetching transfers', 'error' => $e->getMessage()], 500);
            }
        }

    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'store_id' => 'required|exists:store,store_id',
                'transfer_date' => 'required|date',
                'status' => 'required|string|max:10'
            ]);

            return WarehouseTransfer::create($validatedData);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id) { 
        try { 
            
            $transfer = WarehouseTransfer::with(['store', 'details.product'])->findOrFail($id); 
            // Hide the created_at and updated_at fields 
            $transfer->makeHidden(['created_at', 'updated_at']); 
            $transfer->store->makeHidden(['created_at', 'updated_at', 'phone', 'address', 'status', 'city', 'state']); 
            foreach ($transfer->details as $detail) { $detail->makeHidden(['created_at', 'updated_at']); 
                $detail->product->makeHidden(['created_at', 'updated_at', 'sku', 'brand', 'description', 'status', 'image', 'category_id', 'supplier_id', 'type', 'volume', 'unit']); 
            } 
                // Customize the response to include only the store name 
                $response = $transfer->toArray(); $response['store'] = $transfer->store->name; 
                return response()->json($response); 
            } catch (\Exception $e) {
                  return response()->json(['message' => 'An error occurred while fetching the transfer', 'error' => $e->getMessage()], 500); 
                } 
            } 

    public function destroy($id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);
        $transfer->delete();

        return response()->json(['message' => 'Warehouse transfer deleted successfully']);
    }

     // Add the stock to the inv of the store
     public function updateStoreStock($orderID)
     {
         DB::beginTransaction(); // Start a transaction for atomic operations
     
         try {
             // Get the products and their quantities from the order
             $products = WarehouseTransferDetail::where('transfer_id', $orderID)->get();
     
             if ($products->isEmpty()) {
                 return response()->json(['message' => 'No products found for this order'], 404);
             }
     
             // Get the store ID from the order
             $storeID = WarehouseTransfer::where('transfer_id', $orderID)->value('store_id');
             if (!$storeID) {
                 return response()->json(['message' => 'Store not found for this order'], 404);
             }
     
             // Update the status of the order to 'Delivered'
             WarehouseTransfer::where('transfer_id', $orderID)->update(['status' => 'Delivered']);
     
             foreach ($products as $productOrder) {
                 // Update the store's inventory
                 $storeInventory = Inventory::where('product_id', $productOrder->product_id)
                     ->where('store_id', $storeID)
                     ->first();
     
                 if ($storeInventory) {
                     // If the product exists in the store's inventory, update the stock
                     $storeInventory->stock += $productOrder->quantity;
                     $storeInventory->save();
                 } else {
                     // If the product does not exist in the store's inventory, create a new record
                     Inventory::create([
                         'product_id' => $productOrder->product_id,
                         'store_id' => $storeID,
                         'stock' => $productOrder->quantity,
                         'Reserved_Stock' => 0, // Default Reserved_Stock to 0
                     ]);
                 }
     
                 // Update the warehouse's inventory
                 $warehouseInventory = Inventory::where('product_id', $productOrder->product_id)
                     ->where('warehouse_id', 1)
                     ->first();
     
                 if ($warehouseInventory) {
                     // Subtract the quantity from Reserved_Stock
                     if ($warehouseInventory->Reserved_Stock < $productOrder->quantity) {
                         return response()->json(['message' => "Not enough reserved stock to release {$productOrder} < {$productOrder->quantity}"], 400);
                     }
     
                     $warehouseInventory->Reserved_Stock -= $productOrder->quantity;
                     $warehouseInventory->save();
                 } else {
                     return response()->json(['message' => 'Warehouse inventory record not found'], 404);
                 }
             }
     
             DB::commit(); // Commit the transaction
             return response()->json(['message' => 'Stock updated successfully!'], 200);
     
         } catch (\Exception $e) {
             DB::rollBack(); // Rollback the transaction in case of error
             return response()->json([
                 'message' => 'An error occurred while updating store stock',
                 'error' => $e->getMessage(),
                 'stack' => $e->getTraceAsString(),
             ], 500);
         }
     }
     

     public function updStatusOrder($orderID){
        try {
        WarehouseTransfer::where('transfer_id', $orderID)->update(['status' => 'Delivering']);

        return response()->json(['message'=>'Status Changed Successfully']);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while updating status',
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], 500);
        }
     }

     public function getWarehouseTransfer($transferID){
        try {
        $transfer = WarehouseTransfer::with(['details', 'store'])->findOrFail($transferID);
            return response()->json($transfer);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the transfer',
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function updateWarehouseTransfer(Request $request){
        $data = $request->all(); // Esto obtiene todo el body enviado
        try {
            $transfer = WarehouseTransfer::findOrFail($data['id']);
            $transfer->update(["transfer_date" => $data['transfer_date']]);
            $transfer->save();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the transfer',
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], 500);
        }
    }
}
