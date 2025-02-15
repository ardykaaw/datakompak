<?php

namespace App\Http\Controllers;

use App\Models\IkhtisarHarian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Ambil data 7 hari terakhir
        $startDate = Carbon::now()->subDays(7);
        
        $data = IkhtisarHarian::with(['unit', 'machine'])
            ->where('created_at', '>=', $startDate)
            ->get()
            ->map(function ($record) {
                // Hitung efisiensi berdasarkan produksi netto dan bruto
                $efficiency = $record->gross_production > 0 
                    ? round(($record->net_production / $record->gross_production) * 100, 2)
                    : 0;

                // Hitung status berdasarkan jam operasi
                $status = $record->operating_hours > 0 ? 'operational' : 'maintenance';

                // Hitung performa unit
                $eaf = $record->eaf ?? 0; // Equipment Availability Factor
                $sof = $record->sof ?? 0; // Scheduled Outage Factor
                $efor = $record->efor ?? 0; // Equivalent Forced Outage Rate

                return (object)[
                    'unit' => $record->unit->name,
                    'value' => $record->gross_production,
                    'efficiency' => $efficiency,
                    'operating_hours' => $record->operating_hours,
                    'status' => $status,
                    'performance' => [
                        'eaf' => $eaf,
                        'sof' => $sof,
                        'efor' => $efor
                    ],
                    'fuel_usage' => [
                        'hsd' => $record->hsd_fuel,
                        'b35' => $record->b35_fuel,
                        'mfo' => $record->mfo_fuel,
                        'total' => $record->total_fuel
                    ],
                    'nphr' => $record->nphr, // Net Plant Heat Rate
                    'sfc_scc' => $record->sfc_scc // Specific Fuel Consumption
                ];
            });

        return view('analytics', compact('data'));
    }
} 