<?php

namespace App\Http\Controllers;

use App\Models\ProductSupplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    public function index()
    {
        return ProductSupplier::with(['product', 'supplier'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'supplier_id' => 'required|exists:supplier,supplier_id'
        ]);

        return ProductSupplier::create($validatedData);
    }

    public function show($product_id, $supplier_id)
    {
        $productSupplier = ProductSupplier::where('product_id', $product_id)
            ->where('supplier_id', $supplier_id)
            ->with(['product', 'supplier'])
            ->firstOrFail();

        return response()->json($productSupplier);
    }

    public function update(Request $request, $product_id, $supplier_id)
    {
        $productSupplier = ProductSupplier::where('product_id', $product_id)
            ->where('supplier_id', $supplier_id)
            ->firstOrFail();

        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'supplier_id' => 'required|exists:supplier,supplier_id'
        ]);

        $productSupplier->update($validatedData);

        return response()->json(['message' => 'Product-supplier relationship updated successfully', 'data' => $productSupplier]);
    }

    public function destroy($product_id, $supplier_id)
    {
        $productSupplier = ProductSupplier::where('product_id', $product_id)
            ->where('supplier_id', $supplier_id)
            ->firstOrFail();

        $productSupplier->delete();

        return response()->json(['message' => 'Product-supplier relationship deleted successfully']);
    }
}
