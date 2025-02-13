<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Machine;
use Illuminate\Http\Request;

class UnitMachineController extends Controller
{
    public function index()
    {
        $units = Unit::with('machines')->get();
        return view('unit-mesin.index', compact('units'));
    }

    public function create()
    {
        return view('unit-mesin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Unit::create($validated);

        return redirect()->route('unit-mesin.index')
            ->with('success', 'Unit berhasil ditambahkan');
    }

    public function show(Unit $unitMesin)
    {
        return view('unit-mesin.show', ['unit' => $unitMesin]);
    }

    public function edit(Unit $unitMesin)
    {
        return view('unit-mesin.edit', ['unit' => $unitMesin]);
    }

    public function update(Request $request, Unit $unitMesin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $unitMesin->update($validated);

        return redirect()->route('unit-mesin.index')
            ->with('success', 'Unit berhasil diperbarui');
    }

    public function destroy(Unit $unitMesin)
    {
        $unitMesin->delete();

        return redirect()->route('unit-mesin.index')
            ->with('success', 'Unit berhasil dihapus');
    }

    public function storeMachine(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'specifications' => 'nullable|string',
        ]);

        $unit->machines()->create($validated);

        return redirect()->route('unit-mesin.show', $unit)
            ->with('success', 'Mesin berhasil ditambahkan');
    }
} 