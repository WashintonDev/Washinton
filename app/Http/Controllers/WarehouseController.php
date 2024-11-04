<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return Warehouse::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'phone' => 'required|string|size:10',
            'status' => 'required|string|max:10'
        ]);

        return Warehouse::create($validatedData);
    }

    public function show($id)
    {
        return Warehouse::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'phone' => 'required|string|size:10',
            'status' => 'required|string|max:10'
        ]);

        $warehouse->update($validatedData);

        return $warehouse;
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted successfully']);
    }
}
