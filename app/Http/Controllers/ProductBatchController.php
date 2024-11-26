<?php

namespace App\Http\Controllers;

use App\Models\ProductBatch;
use Illuminate\Http\Request;
use App\Models\Batch;

class ProductBatchController extends Controller
{
    // Obtener todos los lotes de productos
    public function index()
    {
        $productBatches = ProductBatch::with(['product', 'batch'])->get();
        return response()->json($productBatches);
    }

    // Obtener un lote de producto específico
    public function show($id)
    {
        $productBatch = ProductBatch::with(['product', 'batch'])->find($id);

        if (!$productBatch) {
            return response()->json(['message' => 'Product batch not found'], 404);
        }

        return response()->json($productBatch);
    }

public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'batch_id' => 'required|exists:batch,batch_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer',
            'expiration_date' => 'nullable|date',
            'status' => 'required|string|max:10'
        ]);

        $productBatch = ProductBatch::create($validatedData);
        $productBatch->load(['product', 'batch']);

        return response()->json($productBatch, 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    // Eliminar un lote de producto específico
    public function destroy($id)
    {
        $productBatch = ProductBatch::find($id);

        if (!$productBatch) {
            return response()->json(['message' => 'Product batch not found'], 404);
        }

        $productBatch->delete();

        return response()->json(['message' => 'Product batch deleted successfully'], 200);
    }

    public function getSupplierDeliveries($supplier_id)
    {
        $deliveries = ProductBatch::with(['product', 'batch'])
            ->whereHas('product', function ($query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            })
            ->whereHas('batch', function ($query) {
                $query->where('status', 'received');
            })
            ->get();

        return response()->json($deliveries);
    }

    public function getBatchWithProducts($batchID)
    {
        try {
            $batch = Batch::where('code', $batchID)->first();
            if (!$batch) {
                return response()->json(['message' => 'Batch not found'], 404);
            }
    
            $products = ProductBatch::where('batch_id', $batch->batch_id)->get();
    
            $transformedBatch = [
                "batchID" => $batch->batch_id,
                "code" => $batch->code,
                "batchName" => $batch->batch_name,
                "status" => $batch->status,
                "requestedAt" => $batch->requested_at,
                "products" => $products->map(function ($productBatch) {
                    return [
                        "name" => $productBatch->product->name,
                        "quantity" => $productBatch->quantity,
                    ];
                })
            ];
    
            return response()->json($transformedBatch);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
