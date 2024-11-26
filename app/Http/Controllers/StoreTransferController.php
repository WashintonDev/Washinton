<?php

namespace App\Http\Controllers;

use App\Models\StoreTransfer;
use App\Models\StoreTransferDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreTransferController extends Controller
{
    // Obtener todas las transferencias de tienda
    public function index()
    {
        try {
            return StoreTransfer::all();
        } catch (\Exception $e) {
            Log::error('Error fetching store transfers: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching store transfers', 'error' => $e->getMessage()], 500);
        }
    }

    // Crear una nueva transferencia de tienda
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'store_id' => 'required|integer',
                'store_transfer_name' => 'required|string|max:100',
                'status' => 'required|string|max:20',
                'requested_at' => 'nullable|date',
                'received_date' => 'nullable|date',
            ]);

            $storeTransfer = StoreTransfer::create($validatedData);

            return $storeTransfer;
        } catch (\Exception $e) {
            Log::error('Error creating store transfer: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the store transfer', 'error' => $e->getMessage()], 500);
        }
    }

    // Crear una nueva transferencia de tienda con detalles
    public function createTransfer(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'store_id' => 'required|integer|exists:store,store_id',
                'store_transfer_name' => 'required|string|max:100',
                'status' => 'required|string|max:20',
                'requested_at' => 'nullable|date',
                'received_date' => 'nullable|date',
                'details' => 'required|array',
                'details.*.product_id' => 'required|exists:product,product_id',
                'details.*.quantity' => 'required|integer',
                'details.*.status' => 'required|string|max:10',
            ]);

            DB::beginTransaction();

            $storeTransfer = StoreTransfer::create([
                'store_id' => $validatedData['store_id'],
                'store_transfer_name' => $validatedData['store_transfer_name'],
                'status' => $validatedData['status'],
                'requested_at' => $validatedData['requested_at'],
                'received_date' => $validatedData['received_date'],
            ]);

            foreach ($validatedData['details'] as $detail) {
                StoreTransferDetail::create([
                    'store_transfer_id' => $storeTransfer->store_transfer_id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'status' => $detail['status'],
                ]);
            }

            DB::commit();

            return response()->json($storeTransfer->load('details'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating store transfer with details: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the store transfer with details', 'error' => $e->getMessage()], 500);
        }
    }

    // Obtener una transferencia de tienda específica por su ID
    public function show($id)
    {
        try {
            return StoreTransfer::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching store transfer: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the store transfer', 'error' => $e->getMessage()], 500);
        }
    }

    // Actualizar una transferencia de tienda específica por su ID
    public function update(Request $request, $id)
    {
        try {
            $storeTransfer = StoreTransfer::findOrFail($id);

            $validatedData = $request->validate([
                'store_id' => 'required|integer',
                'store_transfer_name' => 'required|string|max:100',
                'status' => 'required|string|max:20',
                'requested_at' => 'nullable|date',
                'received_date' => 'nullable|date',
            ]);

            $storeTransfer->update($validatedData);

            return $storeTransfer;
        } catch (\Exception $e) {
            Log::error('Error updating store transfer: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the store transfer', 'error' => $e->getMessage()], 500);
        }
    }

    // Eliminar una transferencia de tienda específica por su ID
    public function destroy($id)
    {
        try {
            $storeTransfer = StoreTransfer::findOrFail($id);
            $storeTransfer->delete();

            return response()->json(['message' => 'Store transfer deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting store transfer: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the store transfer', 'error' => $e->getMessage()], 500);
        }
    }
}