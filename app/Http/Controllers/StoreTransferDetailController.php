<?php

namespace App\Http\Controllers;

use App\Models\StoreTransferDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreTransferDetailController extends Controller
{
    // Obtener todos los detalles de transferencias de tienda
    public function index()
    {
        try {
            return StoreTransferDetail::all();
        } catch (\Exception $e) {
            Log::error('Error fetching store transfer details: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching store transfer details', 'error' => $e->getMessage()], 500);
        }
    }

    // Crear un nuevo detalle de transferencia de tienda
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'store_transfer_id' => 'required|exists:store_transfer,store_transfer_id',
                'product_id' => 'required|exists:product,product_id',
                'quantity' => 'required|integer',
                'status' => 'required|string|max:10',
            ]);

            $storeTransferDetail = StoreTransferDetail::create($validatedData);

            return $storeTransferDetail;
        } catch (\Exception $e) {
            Log::error('Error creating store transfer detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the store transfer detail', 'error' => $e->getMessage()], 500);
        }
    }

    // Obtener un detalle de transferencia de tienda específico por su ID
    public function show($id)
    {
        try {
            return StoreTransferDetail::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching store transfer detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the store transfer detail', 'error' => $e->getMessage()], 500);
        }
    }

    // Actualizar un detalle de transferencia de tienda específico por su ID
    public function update(Request $request, $id)
    {
        try {
            $storeTransferDetail = StoreTransferDetail::findOrFail($id);

            $validatedData = $request->validate([
                'store_transfer_id' => 'required|exists:store_transfer,store_transfer_id',
                'product_id' => 'required|exists:product,product_id',
                'quantity' => 'required|integer',
                'status' => 'required|string|max:10',
            ]);

            $storeTransferDetail->update($validatedData);

            return $storeTransferDetail;
        } catch (\Exception $e) {
            Log::error('Error updating store transfer detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the store transfer detail', 'error' => $e->getMessage()], 500);
        }
    }

    // Eliminar un detalle de transferencia de tienda específico por su ID
    public function destroy($id)
    {
        try {
            $storeTransferDetail = StoreTransferDetail::findOrFail($id);
            $storeTransferDetail->delete();

            return response()->json(['message' => 'Store transfer detail deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting store transfer detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the store transfer detail', 'error' => $e->getMessage()], 500);
        }
    }
}