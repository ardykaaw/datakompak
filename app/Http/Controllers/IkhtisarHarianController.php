<?php

namespace App\Http\Controllers;

use App\Models\IkhtisarHarian;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IkhtisarHarianController extends Controller
{
    public function index()
    {
        $units = Unit::with('machines')->get();
        $todayData = IkhtisarHarian::with(['unit', 'machine'])
            ->whereDate('created_at', today())
            ->get();
        
        return view('ikhtisar-harian', compact('units', 'todayData'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'data.*.unit_id' => 'required|exists:units,id',
            'data.*.machine_id' => 'required|exists:machines,id',
            'data.*.installed_power' => 'required|numeric',
            'data.*.dmn_power' => 'required|numeric',
            'data.*.capable_power' => 'required|numeric',
            'data.*.peak_load' => 'required|numeric',
            'data.*.off_peak_load' => 'required|numeric',
            'data.*.gross_production' => 'required|numeric',
            'data.*.net_production' => 'required|numeric',
            'data.*.operating_hours' => 'required|numeric',
            'data.*.planned_outage' => 'required|numeric',
            'data.*.maintenance_outage' => 'required|numeric',
            'data.*.forced_outage' => 'required|numeric',
        ]);

        foreach ($data['data'] as $machineData) {
            IkhtisarHarian::create($machineData);
        }

        return back()->with('success', 'Data berhasil disimpan');
    }

    public function view(Request $request)
    {
        $query = IkhtisarHarian::with(['machine.unit', 'unit']);
        
        if ($request->unit) {
            $query->whereHas('machine.unit', function($q) use ($request) {
                $q->where('id', $request->unit);
            });
        }
        
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $data = $query->latest()->paginate(15);
        $units = Unit::all();

        return view('ikhtisar-harian-view', compact('data', 'units'));
    }
}   