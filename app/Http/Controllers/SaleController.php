<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        // Obtener todas las ventas con la tienda asociada y los productos a través de los detalles
        return Sale::with(['store', 'details.product'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_id' => 'required|exists:store,store_id',
            'sale_date' => 'required|date',
            'total_amount' => 'required|numeric'
        ]);

        return Sale::create($validatedData);
    }

    public function show($id)
    {
        // Obtener una venta específica con la tienda asociada y los productos a través de los detalles
        return Sale::with(['store', 'details.product'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json(['message' => 'Sale deleted successfully']);
    }

    public function update(Request $request, $id)
{
    $sale = Sale::findOrFail($id);

    $validatedData = $request->validate([
        'store_id' => 'required|exists:store,store_id',
        'sale_date' => 'required|date',
        'total_amount' => 'required|numeric'
    ]);

    $sale->update($validatedData);

    return response()->json(['message' => 'Sale updated successfully', 'sale' => $sale]);
}

}
