<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    public function index()
    {
        try {
            return Store::all();
        } catch (\Exception $e) {
            Log::error('Error fetching stores: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching stores', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:50',
                'phone' => 'required|string|size:10',
                'address' => 'nullable|string',
                'status' => 'required|string|max:10',
                'city' => 'required|string|max:30',
                'state' => 'required|string|max:30'
            ]);

            return Store::create($validatedData);
        } catch (\Exception $e) {
            Log::error('Error creating store: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the store', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            return Store::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching store: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the store', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:50',
                'phone' => 'required|string|size:10',
                'address' => 'nullable|string',
                'status' => 'required|string|max:10',
                'city' => 'required|string|max:30',
                'state' => 'required|string|max:30'
            ]);

            $store->update($validatedData);

            return $store;
        } catch (\Exception $e) {
            Log::error('Error updating store: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the store', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);
            $store->delete();

            return response()->json(['message' => 'Store deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting store: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the store', 'error' => $e->getMessage()], 500);
        }
    }

    public function store_labels()
    {
        try {
            $store = Store::all();

            if ($store) {
                $labels = $store->map(function ($stores) {
                    return [
                        'label' => $stores->name,
                        'value' => $stores->store_id,
                    ];
                });

                return response()->json($labels);
            } else {
                return response()->json(['message' => 'No stores available']);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching store labels: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching store labels', 'error' => $e->getMessage()], 500);
        }
    }

    //endpoint to return the total stock in a specific store
    public function store_inventory(Request $request, $storeID)
    {
        try {
            $query = Inventory::where('store_id', $storeID);
    
            if ($request->has('product_id')) {
                $query->where('product_id', $request->input('product_id'));
            }

            $storeInv = $query->with(['product', 'store'])->get();
    
            if ($storeInv->isEmpty()) {
                return response()->json(['message' => 'No inventories found for the given parameters'], 404);
            }

            return response()->json($storeInv);
        } catch (\Exception $e) {

            Log::error('Error fetching store inventory: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the store inventory', 'error' => $e->getMessage()], 500);
        }
    }
    

}
