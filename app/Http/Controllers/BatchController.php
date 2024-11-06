<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'batch_name' => 'required|string|max:100',
        ]);

        $batch = Batch::create($validatedData);

        return response()->json($batch, 201);
    }
}