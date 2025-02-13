<?php

namespace App\Http\Controllers;

use App\Models\DailyUnitRecord;
use App\Models\PowerUnit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyUnitRecordController extends Controller
{
    public function index()
    {
        $todayData = DailyUnitRecord::with('powerUnit')
            ->whereDate('record_date', Carbon::today())
            ->get();

        // Get all power units for the dropdown
        $powerUnits = [
            ['name' => 'MAK 8M 453 AK #1', 'power' => 2544],
            ['name' => 'MAK 8M 453 AK #2', 'power' => 2544],
            ['name' => 'MAK 8M 453 AK #3', 'power' => 2544],
            ['name' => 'MAK 8M 453 C #4', 'power' => 2800],
            ['name' => 'MAK 8M 453 C #5', 'power' => 2800],
            ['name' => 'DAIHATSU 12 DS 32 #1', 'power' => 3000],
            ['name' => 'CATERPILLAR D 3616 TA', 'power' => 4700],
        ];

        return view('ikhtisar-harian', compact('todayData', 'powerUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_name' => 'required|string',
            'installed_power' => 'required|numeric',
            'silm_power' => 'required|numeric',
            'supply_power' => 'required|numeric',
            'peak_load_day' => 'required|numeric',
            'peak_load_night' => 'required|numeric',
            'gross_production' => 'required|numeric',
            'net_production' => 'required|numeric',
            'aux_power' => 'required|numeric',
            'transformer_losses' => 'required|numeric',
            'operating_hours' => 'required|numeric',
            'planned_maintenance' => 'required|numeric',
            'maintenance_outage' => 'required|numeric',
            'forced_outage' => 'required|numeric',
            'machine_trips' => 'required|integer',
            'electrical_trips' => 'required|integer',
        ]);

        // Find or create power unit
        $powerUnit = PowerUnit::firstOrCreate(
            ['name' => $validated['unit_name']],
            [
                'installed_power' => $validated['installed_power'],
                'silm_power' => $validated['silm_power'],
                'supply_power' => $validated['supply_power'],
            ]
        );

        // Calculate derived values
        $powerRatio = $validated['peak_load_night'] > 0 ? 
            ($validated['peak_load_night'] / $validated['peak_load_day']) * 100 : 0;
            
        $usagePercentage = $validated['gross_production'] > 0 ? 
            (($validated['aux_power'] + $validated['transformer_losses']) / $validated['gross_production']) * 100 : 0;

        // Set operational status based on operating hours
        $operationalStatus = $validated['operating_hours'] > 0 ? 'operational' : 'standby';

        // Create or update daily record
        DailyUnitRecord::updateOrCreate(
            [
                'power_unit_id' => $powerUnit->id,
                'record_date' => Carbon::today()
            ],
            [
                'installed_power' => $validated['installed_power'],
                'silm_power' => $validated['silm_power'],
                'supply_power' => $validated['supply_power'],
                'peak_load_day' => $validated['peak_load_day'],
                'peak_load_night' => $validated['peak_load_night'],
                'power_ratio' => $powerRatio,
                'gross_production' => $validated['gross_production'],
                'net_production' => $validated['net_production'],
                'aux_power' => $validated['aux_power'],
                'transformer_losses' => $validated['transformer_losses'],
                'usage_percentage' => $usagePercentage,
                'operating_hours' => $validated['operating_hours'],
                'planned_maintenance' => $validated['planned_maintenance'],
                'maintenance_outage' => $validated['maintenance_outage'],
                'forced_outage' => $validated['forced_outage'],
                'machine_trips' => $validated['machine_trips'],
                'electrical_trips' => $validated['electrical_trips'],
                'operational_status' => $operationalStatus
            ]
        );

        return redirect()->route('ikhtisar-harian')
            ->with('success', 'Data berhasil disimpan');
    }
} 