<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ProductBatch; // Asegúrate de incluir el modelo ProductBatch
use App\Models\Inventory; // Asegúrate de incluir el modelo Inventory
use Illuminate\Http\Request;

class BatchController extends Controller
{
    // Obtener todos los lotes
    public function index()
    {
        $batches = Batch::all(); // Obtiene todos los lotes
        return response()->json($batches);
    }

    // Obtener un lote específico por su ID
    public function show($id)
    {
        $batch = Batch::find($id);

        if (!$batch) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        return response()->json($batch);
    }

    // Crear un nuevo lote
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|size:10', // Asegúrate de que el código tenga el tamaño correcto
            'batch_name' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'requested_at' => 'nullable|date',
        ]);

        $batch = Batch::create($validatedData);

        return response()->json($batch, 201);
    }

    // Actualizar un lote específico por su ID
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'batch_name' => 'required|string|max:100',
            'status' => 'required|string|max:20', // Nueva validación para el status
            'requested_at' => 'nullable|date', // Nueva validación para la fecha solicitada
        ]);

        $batch = Batch::find($id);
        
        if (!$batch) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        $batch->update($validatedData);

        return response()->json($batch);
    }

    // Eliminar un lote específico por su ID
    public function destroy($id)
    {
        $batch = Batch::find($id);

        if (!$batch) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        $batch->delete();

        return response()->json(['message' => 'Batch deleted successfully'], 200);
    }

    // Actualizar el estado de una batch y sumar productos al inventario
    public function updateStatus(Request $request)
    {
        // Validar que se envíe el código de la batch
        $validatedData = $request->validate([
            'code' => 'required|string',
        ]);

        // Buscar la batch por código
        $batch = Batch::where('code', $validatedData['code'])->first();

        if (!$batch) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        // Actualizar el estado de la batch a 'received'
        $batch->status = 'received';
        $batch->save();

        // Obtener todos los productos de esta batch
        $productsInBatch = ProductBatch::where('batch_id', $batch->batch_id)->get();

        // Sumar las cantidades al inventario correspondiente
        foreach ($productsInBatch as $productBatch) {
            $inventory = Inventory::where('product_id', $productBatch->product_id)
                ->where('warehouse_id', 1) // Reemplaza con el ID de tu warehouse
                ->first();

            if ($inventory) {
                $inventory->stock += $productBatch->quantity; // Sumar la cantidad
                $inventory->save();
            }
        }

        return response()->json(['message' => 'Batch status updated and stock adjusted successfully'], 200);
    }
}
