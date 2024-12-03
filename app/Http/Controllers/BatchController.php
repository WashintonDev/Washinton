<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ProductBatch; // Asegúrate de incluir el modelo ProductBatch
use App\Models\Inventory; // Asegúrate de incluir el modelo Inventory
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|size:10', // Ensure the code has the correct size
                'batch_name' => 'required|string|max:100',
                'status' => 'required|string|max:20',
                'requested_at' => 'nullable|date',
            ]);
    
            $batch = Batch::create($validatedData);
    
            return response()->json($batch, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Batch creation failed', 'details' => $e->getMessage()], 500);
        }
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

    // Iterar sobre los productos en la batch
    foreach ($productsInBatch as $productBatch) {
        $inventory = Inventory::where('product_id', $productBatch->product_id)
            ->where('warehouse_id', 1) // Reemplaza con el ID de tu warehouse
            ->first();

        if ($inventory) {
            // Si ya existe, sumar la cantidad al stock
            $inventory->stock += $productBatch->quantity;
            $inventory->save();
        } else {
            // Si no existe, crear un nuevo registro de inventario
            Inventory::create([
                'product_id' => $productBatch->product_id,
                'warehouse_id' => 1, // Reemplaza con el ID de tu warehouse
                'store_id' => null, // Si no usas tienda, puede quedar como null
                'stock' => $productBatch->quantity,
                'Reserved_Stock' => 0, // Inicializar el stock reservado en 0
            ]);
        }
    }

    return response()->json(['message' => 'Batch status updated and stock adjusted successfully'], 200);
}



public function bulkUpdate(Request $request)
{
    try {
        $data = $request->validate([
            'batches' => 'required|array',
            'batches.*.batch_id' => 'required|exists:batch,batch_id',
            'batches.*.status' => 'nullable|string|max:20',
            'batches.*.reasons' => 'nullable|string|max:255',
        ]);

        // Validar reglas adicionales para cada batch manualmente
        foreach ($data['batches'] as $batch) {
            if (in_array($batch['status'], ['in_process', 'cancelled']) && empty($batch['reasons'])) {
                return response()->json([
                    'error' => "The 'reasons' field is required for status '{$batch['status']}'",
                    'batch_id' => $batch['batch_id']
                ], 422);
            }
        }

        DB::beginTransaction();

        foreach ($data['batches'] as $batchData) {
            $batch = Batch::where('batch_id', $batchData['batch_id'])->firstOrFail();

            // Actualizar el batch, incluyendo el campo 'reasons' si está presente
            $batch->fill($batchData);
            $batch->save();

            // Ajustar inventarios si el estado es 'received'
            if (isset($batchData['status']) && $batchData['status'] === 'received') {
                $productsInBatch = ProductBatch::where('batch_id', $batch->batch_id)->get();

                foreach ($productsInBatch as $productBatch) {
                    $inventory = Inventory::where('product_id', $productBatch->product_id)
                        ->where('warehouse_id', 1) // Ajusta según el ID de tu almacén
                        ->first();

                    if ($inventory) {
                        // Si el inventario existe, sumar la cantidad
                        $inventory->stock += $productBatch->quantity;
                        $inventory->save();
                    } else {
                        // Crear un nuevo registro de inventario si no existe
                        Inventory::create([
                            'product_id' => $productBatch->product_id,
                            'warehouse_id' => 1, // Ajusta según el ID de tu almacén
                            'store_id' => null, // Si no aplica tienda, puede ser null
                            'stock' => $productBatch->quantity,
                            'Reserved_Stock' => 0, // Inicializar el stock reservado en 0
                        ]);
                    }
                }
            }
        }

        DB::commit();
        return response()->json(['message' => 'Bulk update successful'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Bulk update failed', 'details' => $e->getMessage()], 500);
    }
}


    public function patchUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'batch_name' => 'sometimes|string|max:100',
            'status' => 'sometimes|string|max:20',
            'requested_at' => 'sometimes|date',
            'reasons' => 'required_if:status,in_process,cancelled|string|max:255',
        ]);
    
        $batch = Batch::find($id);
    
        if (!$batch) {
            return response()->json(['message' => 'Batch not found'], 404);
        }
    
        foreach ($validatedData as $key => $value) {
            $batch->{$key} = $value;
        }
        $batch->save();
    
        if (isset($validatedData['status']) && $validatedData['status'] === 'received') {
            $productsInBatch = ProductBatch::where('batch_id', $batch->batch_id)->get();
    
            foreach ($productsInBatch as $productBatch) {
                $inventory = Inventory::where('product_id', $productBatch->product_id)
                    ->where('warehouse_id', 1)
                    ->first();
    
                if ($inventory) {
                    $inventory->stock += $productBatch->quantity;
                    $inventory->save();
                }
            }
        }
    
        return response()->json([
            'message' => 'Batch status updated successfully',
            'data' => $batch,
        ], 200);
    }
}
