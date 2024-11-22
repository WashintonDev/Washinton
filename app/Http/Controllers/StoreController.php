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
    public function store_inventory($storeID){
        try{
            $store_inv = Inventory::where('store_id', $storeID)->get();

            if ($store_inv){
                return response()->json($store_inv);
            }else{
                return response()->json(['message'=>'No products in that store']);
            }
        }catch(\Exception $e){

        }
    }

}
