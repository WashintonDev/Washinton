<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransfer;
use Exception;
use Illuminate\Http\Request;

class WarehouseTransferController extends Controller
{
    public function index()
    {
        return WarehouseTransfer::with('details')->get();
    }

    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'store_id' => 'required|exists:store,store_id',
                'transfer_date' => 'required|date',
                'status' => 'required|string|max:10'
            ]);

            return WarehouseTransfer::create($validatedData);
            
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return WarehouseTransfer::with('details')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        $validatedData = $request->validate([
            'store_id' => 'required|exists:store,store_id',
            'transfer_date' => 'required|date',
            'status' => 'required|string|max:10'
        ]);

        $transfer->update($validatedData);

        return response()->json(['message' => 'Warehouse transfer updated successfully', 'data' => $transfer]);
    }

    public function destroy($id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);
        $transfer->delete();

        return response()->json(['message' => 'Warehouse transfer deleted successfully']);
    }
}
