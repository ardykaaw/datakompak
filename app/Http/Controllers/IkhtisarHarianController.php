<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Machine;
use App\Models\IkhtisarHarian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IkhtisarHarianController extends Controller
{
    public function index()
    {
        $units = Unit::with('machines')->get();
        $todayData = IkhtisarHarian::with(['unit', 'machine'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        return view('ikhtisar-harian', compact('units', 'todayData'));
    }

    public function view()
    {
        $units = Unit::all();
        $data = IkhtisarHarian::with(['unit', 'machine'])
            ->when(request('unit'), function($query) {
                return $query->where('unit_id', request('unit'));
            })
            ->when(request('start_date') && request('end_date'), function($query) {
                return $query->whereBetween('created_at', [
                    Carbon::parse(request('start_date')),
                    Carbon::parse(request('end_date'))->endOfDay()
                ]);
            })
            ->latest()
            ->paginate(15);

        return view('ikhtisar-harian-view', compact('data', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data.*.installed_power' => 'required|numeric',
            'data.*.dmn_power' => 'required|numeric',
            'data.*.capable_power' => 'required|numeric',
            'data.*.peak_load_day' => 'required|numeric',
            'data.*.peak_load_night' => 'required|numeric',
            'data.*.gross_production' => 'required|numeric',
            'data.*.net_production' => 'required|numeric',
            'data.*.operating_hours' => 'required|numeric'
        ]);

        foreach ($request->data as $machineId => $data) {
            IkhtisarHarian::create([
                'machine_id' => $machineId,
                'unit_id' => Machine::find($machineId)->unit_id,
                ...$data
            ]);
        }

        return redirect()->route('ikhtisar-harian.index')
            ->with('success', 'Data berhasil disimpan!');
    }

    public function getMachines(Unit $unit)
    {
        return response()->json($unit->machines);
    }

    public function edit($id)
    {
        $data = IkhtisarHarian::with(['unit', 'machine'])->findOrFail($id);
        $units = Unit::all();
        return view('ikhtisar-harian.edit', compact('data', 'units'));
    }

    public function update(Request $request, $id)
    {
        $ikhtisarHarian = IkhtisarHarian::findOrFail($id);
        
        $request->validate([
            'installed_power' => 'required|numeric',
            'dmn_power' => 'required|numeric',
            'capable_power' => 'required|numeric',
            'peak_load_day' => 'required|numeric',
            'peak_load_night' => 'required|numeric',
            'gross_production' => 'required|numeric',
            'net_production' => 'required|numeric',
            'operating_hours' => 'required|numeric'
        ]);

        $ikhtisarHarian->update($request->all());

        return redirect()->route('ikhtisar-harian.view')
            ->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ikhtisarHarian = IkhtisarHarian::findOrFail($id);
        $ikhtisarHarian->delete();

        return redirect()->route('ikhtisar-harian.view')
            ->with('success', 'Data berhasil dihapus!');
    }
}   