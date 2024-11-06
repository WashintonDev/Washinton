<?php

namespace App\Http\Controllers;

use App\Models\ProductBatch;
use Illuminate\Http\Request;

class ProductBatchController extends Controller
{
    public function index()
    {
        $productBatches = ProductBatch::with(['product', 'batch'])->get();
        return response()->json($productBatches);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'batch_id' => 'required|exists:batches,batch_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer',
            'received_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'status' => 'required|string|max:10'
        ]);

        $productBatch = ProductBatch::create($validatedData);

        return response()->json($productBatch, 201);
    }
}