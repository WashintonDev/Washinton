<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransferDetail;
use Illuminate\Http\Request;

class WarehouseTransferDetailController extends Controller
{
    public function index()
    {
        return WarehouseTransferDetail::with(['transfer', 'product'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'transfer_id' => 'required|exists:warehouse_transfer,transfer_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer'
        ]);

        return WarehouseTransferDetail::create($validatedData);
    }

    public function show($id)
    {
        return WarehouseTransferDetail::with(['transfer', 'product'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $transferDetail = WarehouseTransferDetail::findOrFail($id);

        $validatedData = $request->validate([
            'transfer_id' => 'required|exists:warehouse_transfer,transfer_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer'
        ]);

        $transferDetail->update($validatedData);

        return response()->json(['message' => 'Warehouse transfer detail updated successfully', 'data' => $transferDetail]);
    }

    public function destroy($id)
    {
        $transferDetail = WarehouseTransferDetail::findOrFail($id);
        $transferDetail->delete();

        return response()->json(['message' => 'Warehouse transfer detail deleted successfully']);
    }
}
