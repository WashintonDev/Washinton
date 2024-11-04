<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function index()
    {
        return SaleDetail::with(['sale', 'product'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sale_id' => 'required|exists:sale,sale_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer',
            'price_per_unit' => 'required|numeric',
            'total_price' => 'required|numeric'
        ]);

        return SaleDetail::create($validatedData);
    }

    public function show($id)
    {
        return SaleDetail::with(['sale', 'product'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $saleDetail = SaleDetail::findOrFail($id);
        $saleDetail->delete();

        return response()->json(['message' => 'Sale detail deleted successfully']);
    }
}
