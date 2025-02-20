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

    public function destroyMachine(Unit $unit, Machine $machine)
    {
        // Pastikan mesin benar-benar milik unit ini
        if ($machine->unit_id !== $unit->id) {
            return back()->with('error', 'Mesin tidak ditemukan dalam unit ini.');
        }

        $machine->delete();
        return back()->with('success', 'Mesin berhasil dihapus.');
    }

    public function mesinIndex(Request $request)
    {
        $search = $request->input('search');
        
        $units = Unit::with(['machines' => function($query) {
            $query->orderBy('name');
        }])->get();
        
        // Get all machines paginated with search
        $machines = Machine::with('unit')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('unit', function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('name')
            ->paginate(10);
            
        if($request->ajax()) {
            return view('unit-mesin.partials.machine-table', compact('machines'))->render();
        }
            
        return view('unit-mesin.mesin', compact('units', 'machines'));
    }

    public function unitIndex(Request $request)
    {
        $search = $request->input('search');
        
        $units = Unit::with('machines')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        if($request->ajax()) {
            return view('unit-mesin.partials.unit-table', compact('units'))->render();
        }
            
        return view('unit-mesin.unit', compact('units'));
    }

    public function editMachine(Unit $unit, Machine $machine)
    {
        // Ambil semua unit untuk dropdown
        $units = Unit::all();
        
        return view('unit-mesin.edit-machine', compact('unit', 'machine', 'units'));
    }

    public function updateMachine(Request $request, Unit $unit, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'unit_id' => 'required|exists:units,id',
            'dmn' => 'required|numeric',
            'dmp' => 'required|numeric',
            'load' => 'required|numeric',
        ]);

        $machine->update($validated);

        return redirect()->route('unit-mesin.mesin')
            ->with('success', 'Mesin berhasil diperbarui');
    }
} 