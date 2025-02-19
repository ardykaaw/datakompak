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
            'data.*.unit_id' => 'required|exists:units,id',
            'data.*.machine_id' => 'required|exists:machines,id',
            'data.*.installed_power' => 'required|numeric',
            'data.*.dmn_power' => 'required|numeric',
            'data.*.capable_power' => 'required|numeric',
            'data.*.peak_load_day' => 'required|numeric',
            'data.*.peak_load_night' => 'required|numeric',
            'data.*.kit_ratio' => 'required|numeric',
            'data.*.gross_production' => 'required|numeric',
            'data.*.net_production' => 'required|numeric',
            'data.*.aux_power' => 'required|numeric',
            'data.*.transformer_losses' => 'required|numeric',
            'data.*.usage_percentage' => 'required|numeric',
            'data.*.period_hours' => 'required|numeric',
            'data.*.operating_hours' => 'required|numeric',
            'data.*.standby_hours' => 'required|numeric',
            'data.*.planned_outage' => 'required|numeric',
            'data.*.maintenance_outage' => 'required|numeric',
            'data.*.forced_outage' => 'required|numeric',
            'data.*.trip_machine' => 'required|numeric',
            'data.*.trip_electrical' => 'required|numeric',
            'data.*.efdh' => 'required|numeric',
            'data.*.epdh' => 'required|numeric',
            'data.*.eudh' => 'required|numeric',
            'data.*.esdh' => 'required|numeric',
            'data.*.eaf' => 'required|numeric',
            'data.*.sof' => 'required|numeric',
            'data.*.efor' => 'required|numeric',
            'data.*.sdof' => 'required|numeric',
            'data.*.ncf' => 'required|numeric',
            'data.*.nof' => 'required|numeric',
            'data.*.jsi' => 'required|numeric',
            'data.*.hsd_fuel' => 'required|numeric',
            'data.*.b35_fuel' => 'required|numeric',
            'data.*.mfo_fuel' => 'required|numeric',
            'data.*.total_fuel' => 'required|numeric',
            'data.*.water_usage' => 'required|numeric',
            'data.*.meditran_oil' => 'required|numeric',
            'data.*.salyx_420' => 'required|numeric',
            'data.*.salyx_430' => 'required|numeric',
            'data.*.travolube_a' => 'required|numeric',
            'data.*.turbolube_46' => 'required|numeric',
            'data.*.turbolube_68' => 'required|numeric',
            'data.*.total_oil' => 'required|numeric',
            'data.*.sfc_scc' => 'required|numeric',
            'data.*.nphr' => 'required|numeric',
            'data.*.slc' => 'required|numeric',
            'data.*.notes' => 'nullable|string|max:255',
            'data.*.off_peak_load' => 'required|numeric',
            'data.*.trip_non_omc' => 'required|numeric',
        ]);

        try {
            foreach ($request->data as $machineId => $data) {
                $machine = Machine::findOrFail($machineId);
                
                IkhtisarHarian::create([
                    'machine_id' => $machineId,
                    'unit_id' => $machine->unit_id,
                    ...$data
                ]);
            }

            return redirect()->route('ikhtisar-harian.index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
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