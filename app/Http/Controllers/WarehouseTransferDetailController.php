<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransferDetail;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\WarehouseTransfer;
use App\Services\FirebaseService;


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

         //get the deitals
         $transfer = WarehouseTransfer::with(['store', 'details.product'])->findOrFail($transferId); 

         // Hide the created_at and updated_at fields 
         $transfer->makeHidden(['created_at', 'updated_at']); 
         $transfer->store->makeHidden(['created_at', 'updated_at', 'phone', 'address', 'status', 'city', 'state']); 
         foreach ($transfer->details as $detail) { $detail->makeHidden(['created_at', 'updated_at']); 
             $detail->product->makeHidden(['created_at', 'updated_at', 'sku', 'brand', 'description', 'status', 'image', 'category_id', 'supplier_id', 'type', 'volume', 'unit']); 
         } 

         // Customize the response to include only the store name 
         $response = $transfer->toArray();
         $response['store'] = $transfer->store->name;

         $firebaseService = new FirebaseService();
         // Publish the order info to Firebase
         $firebaseService->sendToTopic(
             'warehouse_transfers', // Topic name
             'New Warehouse Transfer Created',
             'A new warehouse transfer has been created.',
            $response
         );

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

    public function openApp($data){
        try{
            $firebaseService = new FirebaseService();
            // Publish the order info to Firebase
            $firebaseService->sendToTopic(
                "openApp_$data->FBID", // Topic name
                'Open App',
                'With Order',
               $data->orderID
            );
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notifyApproval(Request $request) {
        try {

            $firebaseService = new FirebaseService();

            $firebaseService->sendToTopic(
                "notify_Approval", // Topic name
                "Order $request->orderID has been $request->type",
                'An order has been approved, click on the notification to check it out',
                ['orderID' => $request->orderID] 
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
