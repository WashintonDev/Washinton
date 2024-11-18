<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleDetailController extends Controller
{
    public function index()
    {
        try {
            return SaleDetail::with(['sale', 'product'])->get();
        } catch (\Exception $e) {
            Log::error('Error fetching sale details: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching sale details', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sale_id' => 'required|exists:sale,sale_id',
                'product_id' => 'required|exists:product,product_id',
                'quantity' => 'required|integer',
                'price_per_unit' => 'required|numeric',
                'total_price' => 'required|numeric'
            ]);

            return SaleDetail::create($validatedData);
        } catch (\Exception $e) {
            Log::error('Error creating sale detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while creating the sale detail', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            return SaleDetail::with(['sale', 'product'])->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching sale detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while fetching the sale detail', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $saleDetail = SaleDetail::findOrFail($id);

            $validatedData = $request->validate([
                'sale_id' => 'required|exists:sale,sale_id',
                'product_id' => 'required|exists:product,product_id',
                'quantity' => 'required|integer',
                'price_per_unit' => 'required|numeric',
                'total_price' => 'required|numeric'
            ]);

            $saleDetail->update($validatedData);

            return response()->json(['message' => 'Sale detail updated successfully', 'data' => $saleDetail]);
        } catch (\Exception $e) {
            Log::error('Error updating sale detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while updating the sale detail', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $saleDetail = SaleDetail::findOrFail($id);
            $saleDetail->delete();

            return response()->json(['message' => 'Sale detail deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting sale detail: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while deleting the sale detail', 'error' => $e->getMessage()], 500);
        }
    }
}
