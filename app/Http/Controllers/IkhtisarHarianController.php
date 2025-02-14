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
        $units = Unit::all();
        $todayData = IkhtisarHarian::with(['unit', 'machine'])
            ->whereDate('created_at', Carbon::today())
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
}   