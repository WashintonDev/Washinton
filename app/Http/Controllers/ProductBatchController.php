<?php

namespace App\Http\Controllers;

use App\Models\ProductBatch;
use Illuminate\Http\Request;

class ProductBatchController extends Controller
{
    public function index()
    {
        return ProductBatch::with('product')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer',
            'received_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'status' => 'required|string|max:10'
        ]);

        return ProductBatch::create($validatedData);
    }

    public function show($id)
    {
        return ProductBatch::with('product')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $batch = ProductBatch::findOrFail($id);

        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer',
            'received_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'status' => 'required|string|max:10'
        ]);

        $batch->update($validatedData);

        return $batch;
    }

    public function destroy($id)
    {
        $batch = ProductBatch::findOrFail($id);
        $batch->delete();

        return response()->json(['message' => 'Product batch deleted successfully']);
    }
}
