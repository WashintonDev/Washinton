<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'description' => 'nullable|string',
            'email' => 'required|string|email|max:120',
            'phone' => 'required|string|size:10',
            'status' => 'required|string|max:10'
        ]);

        return Supplier::create($validatedData);
    }

    public function show($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:80',
            'description' => 'nullable|string',
            'email' => 'required|string|email|max:120',
            'phone' => 'required|string|size:10',
            'status' => 'required|string|max:10'
        ]);

        $supplier->update($validatedData);

        return $supplier;
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
