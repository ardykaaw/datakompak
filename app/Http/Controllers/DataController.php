<?php

namespace App\Http\Controllers;

use App\Models\DailyUnitRecord;
use App\Models\PowerUnit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DataController extends Controller
{
    public function index()
    {
        // Get today's data for each unit
        $todayData = DailyUnitRecord::with('powerUnit')
            ->whereDate('record_date', Carbon::today())
            ->get();
        
        // Get power units for dropdown
        $powerUnits = PowerUnit::all();
        
        return view('ikhtisar-harian', [
            'todayData' => $todayData,
            'powerUnits' => $powerUnits
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'unit' => 'required|string',
            'installed_power' => 'required|numeric',
            'silm_power' => 'required|numeric',
            'supply_power' => 'required|numeric',
            'peak_load_day' => 'required|numeric',
            'peak_load_night' => 'required|numeric',
            'gross_production' => 'required|numeric',
            'net_production' => 'required|numeric',
            'aux_power' => 'required|numeric',
            'transformer_losses' => 'required|numeric',
            'usage_percentage' => 'required|numeric',
            'period_hours' => 'required|numeric',
            'operating_hours' => 'required|numeric',
            'planned_maintenance' => 'required|numeric',
            'maintenance_outage' => 'required|numeric',
            'forced_outage' => 'required|numeric',
            'machine_trips' => 'required|integer',
            'electrical_trips' => 'required|integer'
        ]);

        // Get or create power unit
        $powerUnit = PowerUnit::firstOrCreate(
            ['name' => $request->unit],
            [
                'installed_power' => $request->installed_power,
                'silm_power' => $request->silm_power,
                'supply_power' => $request->supply_power
            ]
        );

        // Create daily record
        $dailyRecord = new DailyUnitRecord([
            'record_date' => Carbon::today(),
            'installed_power' => $request->installed_power,
            'silm_power' => $request->silm_power,
            'supply_power' => $request->supply_power,
            'peak_load_day' => $request->peak_load_day,
            'peak_load_night' => $request->peak_load_night,
            'power_ratio' => $request->peak_load_day > 0 ? ($request->peak_load_night / $request->peak_load_day) * 100 : 0,
            'gross_production' => $request->gross_production,
            'net_production' => $request->net_production,
            'aux_power' => $request->aux_power,
            'transformer_losses' => $request->transformer_losses,
            'usage_percentage' => $request->usage_percentage,
            'period_hours' => $request->period_hours,
            'operating_hours' => $request->operating_hours,
            'planned_maintenance' => $request->planned_maintenance,
            'maintenance_outage' => $request->maintenance_outage,
            'forced_outage' => $request->forced_outage,
            'machine_trips' => $request->machine_trips,
            'electrical_trips' => $request->electrical_trips,
            'operational_status' => $request->operating_hours > 0 ? 'operational' : 'standby'
        ]);

        $powerUnit->dailyRecords()->save($dailyRecord);

        return redirect()->route('ikhtisar-harian')
                        ->with('success', 'Data berhasil disimpan');
    }
}
