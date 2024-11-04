<?php

namespace App\Http\Controllers;

use App\Models\RestockRequest;
use Illuminate\Http\Request;

class RestockRequestController extends Controller
{
    public function index()
    {
        return RestockRequest::with('details')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'request_date' => 'required|date',
            'status' => 'required|string|max:10'
        ]);

        return RestockRequest::create($validatedData);
    }

    public function show($id)
    {
        return RestockRequest::with('details')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'request_date' => 'required|date',
            'status' => 'required|string|max:10'
        ]);

        $restockRequest->update($validatedData);

        return response()->json(['message' => 'Restock request updated successfully', 'data' => $restockRequest]);
    }

    public function destroy($id)
    {
        $restockRequest = RestockRequest::findOrFail($id);
        $restockRequest->delete();

        return response()->json(['message' => 'Restock request deleted successfully']);
    }
}
