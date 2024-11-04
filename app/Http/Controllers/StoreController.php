<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return Store::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string|size:10',
            'address' => 'nullable|string',
            'status' => 'required|string|max:10',
            'city' => 'required|string|max:30',
            'state' => 'required|string|max:30'
        ]);

        return Store::create($validatedData);
    }

    public function show($id)
    {
        return Store::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string|size:10',
            'address' => 'nullable|string',
            'status' => 'required|string|max:10',
            'city' => 'required|string|max:30',
            'state' => 'required|string|max:30'
        ]);

        $store->update($validatedData);

        return $store;
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return response()->json(['message' => 'Store deleted successfully']);
    }
}
