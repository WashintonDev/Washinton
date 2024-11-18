<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index()
    {
        try {
            // Obtener todas las ventas con la tienda asociada y los productos a través de los detalles
            return Sale::with(['store', 'details.product'])->get();
        } catch (\Exception $e) {
            Log::error('Error fetching sales: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching sales', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'store_id' => 'required|exists:store,store_id',
                'sale_date' => 'required|date',
                'total_amount' => 'required|numeric'
            ]);

            return Sale::create($validatedData);
        } catch (\Exception $e) {
            Log::error('Error creating sale: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the sale', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            // Obtener una venta específica con la tienda asociada y los productos a través de los detalles
            return Sale::with(['store', 'details.product'])->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching sale: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the sale', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            $sale->delete();

            return response()->json(['message' => 'Sale deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting sale: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the sale', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $sale = Sale::findOrFail($id);

            $validatedData = $request->validate([
                'store_id' => 'required|exists:store,store_id',
                'sale_date' => 'required|date',
                'total_amount' => 'required|numeric'
            ]);

            $sale->update($validatedData);

            return response()->json(['message' => 'Sale updated successfully', 'sale' => $sale]);
        } catch (\Exception $e) {
            Log::error('Error updating sale: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the sale', 'error' => $e->getMessage()], 500);
        }
    }
}
