<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IkhtisarHarian;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $monthlyData = IkhtisarHarian::with(['unit', 'machine'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->map(function ($record) {
                // Hitung efisiensi
                $efficiency = $record->gross_production > 0 
                    ? round(($record->net_production / $record->gross_production) * 100, 2)
                    : 0;

                // Tentukan status operasional
                $status = $record->operating_hours > 0 ? 'operational' : 'maintenance';

                // Format tanggal menggunakan accessor dari model
                $date = Carbon::parse($record->created_at)->format('d/m/Y');

                return [
                    'tanggal' => $date,
                    'unit' => $record->unit->name,
                    'machine' => $record->machine->name,
                    'status' => $status,
                    'production' => [
                        'gross' => $record->gross_production,
                        'net' => $record->net_production,
                        'efficiency' => $efficiency
                    ],
                    'operating_data' => [
                        'hours' => $record->operating_hours,
                        'planned_outage' => $record->planned_outage,
                        'maintenance_outage' => $record->maintenance_outage,
                        'forced_outage' => $record->forced_outage
                    ],
                    'performance' => [
                        'eaf' => $record->eaf,
                        'sof' => $record->sof,
                        'efor' => $record->efor,
                        'nphr' => $record->nphr,
                        'sfc_scc' => $record->sfc_scc
                    ],
                    'fuel_usage' => [
                        'hsd' => $record->hsd_fuel,
                        'b35' => $record->b35_fuel,
                        'mfo' => $record->mfo_fuel,
                        'total' => $record->total_fuel
                    ],
                    'notes' => $record->notes
                ];
            });
                          
        return view('reports', compact('monthlyData'));
    }
} 