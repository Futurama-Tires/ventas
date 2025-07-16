<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factor;
use Illuminate\Support\Facades\Log;

class FactorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factor $factor)
    {
        $validated = $request->validate([
            'factor' => 'required|numeric',
        ]);

        try {
            $factor->update($validated);
            return redirect()->route('maxmin.index')->with('editar', 'ok');
        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->route('maxmin.index')->with('editar', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
