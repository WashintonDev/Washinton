<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransfer;
use Exception;
use Illuminate\Http\Request;
use  App\Models\Inventory;
use App\Models\WarehouseTransferDetail;

class WarehouseTransferController extends Controller
{
    public function index()
    {
        return WarehouseTransfer::with('details')->get();
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

    public function show($id)
    {
        return WarehouseTransfer::with('details')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        $validatedData = $request->validate([
            'store_id' => 'required|exists:store,store_id',
            'transfer_date' => 'required|date',
            'status' => 'required|string|max:10'
        ]);

        $transfer->update($validatedData);

        return response()->json(['message' => 'Warehouse transfer updated successfully', 'data' => $transfer]);
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
         try {
             // Get the products and qty of the order
             $products = WarehouseTransferDetail::where('transfer_id', $orderID)->get();
     
             if (!$products) {
                 return response()->json(['message' => 'Batch not found'], 404);
             }
     
             // Update the status of the order to Delivered
             WarehouseTransfer::where('transfer_id', $orderID)->update(['status' => 'Delivered']);
     
          // Sum the quantities to the corresponding inventory 
          foreach ($products as $productOrder) { 
            $storeID = WarehouseTransfer::where('transfer_id', $orderID)->value('store_id'); 
            $inventory = Inventory::where('product_id', $productOrder->product_id)
                ->where('store_id', $storeID)
                ->first(); 
                if ($inventory) { 
                    // If the product exists in the inventory, update the stock 
                    $inventory->stock += $productOrder->quantity; $inventory->save(); 
                } else { 
                    // If the product does not exist in the inventory, create a new inventory record 
                    Inventory::create([ 'product_id' => $productOrder->product_id, 'store_id' => $storeID, 'stock' => $productOrder->quantity]);
                } 
            }
     
             // Return the products as a JSON response
             return response()->json(['message'=>'Stock added succesfully!']);
         } catch (\Exception $e) {
             // Return a JSON response with the error message and stack trace
             return response()->json([
                 'message' => 'An error occurred while updating store stock',
                 'error' => $e->getMessage(),
                 'stack' => $e->getTraceAsString()
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
     
}
