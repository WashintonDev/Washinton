<?php

namespace App\Http\Controllers;

use App\Models\Batch;
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
            'batch_name' => 'required|string|max:100',
            'status' => 'required|string|max:20', // Nueva validación para el status
            'requested_at' => 'nullable|date', // Nueva validación para la fecha solicitada
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
}
