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

    public function view(Request $request)
    {
        $query = IkhtisarHarian::with('machine.unit')
            ->when($request->unit, function($q) use ($request) {
                return $q->whereHas('machine.unit', function($q) use ($request) {
                    $q->where('id', $request->unit);
                });
            })
            ->when($request->start_date, function($q) use ($request) {
                return $q->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($q) use ($request) {
                return $q->where('created_at', '<=', $request->end_date . ' 23:59:59');
            });

        $data = $query->paginate(15);
        $units = Unit::all();

        return view('ikhtisar-harian-view', compact('data', 'units'));
    }
}   