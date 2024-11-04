<?php

namespace App\Http\Controllers;

use App\Models\RestockRequestDetail;
use Illuminate\Http\Request;

class RestockRequestDetailController extends Controller
{
    public function index()
    {
        return RestockRequestDetail::with(['restockRequest', 'product'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'restock_request_id' => 'required|exists:restock_request,restock_request_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer'
        ]);

        return RestockRequestDetail::create($validatedData);
    }

    public function show($id)
    {
        return RestockRequestDetail::with(['restockRequest', 'product'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $restockRequestDetail = RestockRequestDetail::findOrFail($id);

        $validatedData = $request->validate([
            'restock_request_id' => 'required|exists:restock_request,restock_request_id',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer'
        ]);

        $restockRequestDetail->update($validatedData);

        return response()->json(['message' => 'Restock request detail updated successfully', 'data' => $restockRequestDetail]);
    }

    public function destroy($id)
    {
        $restockRequestDetail = RestockRequestDetail::findOrFail($id);
        $restockRequestDetail->delete();

        return response()->json(['message' => 'Restock request detail deleted successfully']);
    }
}
