<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Machine;
use App\Models\MachineLog;
use App\Models\UnitHop;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanKesiapanKitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['machines' => function($query) {
            $query->with(['logs' => function($query) {
                if (request()->has('input_time')) {
                    $query->whereTime('input_time', request('input_time'));
                }
                $query->latest('input_time')->limit(1);
            }]);    
        }])->get();

        // Transform data untuk menampilkan log terbaru
        $units->each(function($unit) {
            // Load latest HOP data
            $latestHop = UnitHop::getLatestHop($unit->id);
            $unit->hop = $latestHop ? $latestHop->hop_value : null;
            
            $unit->machines->each(function($machine) {
                $latestLog = $machine->logs->first();
                if ($latestLog) {
                    $machine->capable_power = $latestLog->capable_power;
                    $machine->supply_power = $latestLog->supply_power;
                    $machine->current_load = $latestLog->current_load;
                    $machine->status = $latestLog->status;
                    $machine->last_update = $latestLog->input_time;
                }
            });
        });

        return view('laporan-kesiapan-kit.index', compact('units'));
    }

    public function create()
    {
        $units = Unit::with('machines')->get();
        return view('laporan-kesiapan-kit.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'input_time' => 'required',
            'data' => 'array',
            'data.*.capable_power' => 'nullable|numeric',
            'data.*.supply_power' => 'nullable|numeric',
            'data.*.current_load' => 'nullable|numeric',
            'data.*.status' => 'nullable|in:OPS,RSH,FO,MO,PO',
            'hop' => 'array',
            'hop.*' => 'nullable|numeric|min:0',
        ]);

        $inputTime = Carbon::parse($request->input_time);

        // Store HOP values
        foreach ($request->hop ?? [] as $unitId => $hopValue) {
            if (!is_null($hopValue)) {
                UnitHop::create([
                    'unit_id' => $unitId,
                    'hop_value' => $hopValue,
                    'input_time' => $inputTime,
                ]);
            }
        }

        // Store the machine data
        foreach ($request->data ?? [] as $machineId => $data) {
            // Check if any field has data
            if (!empty($data['capable_power']) || 
                !empty($data['supply_power']) || 
                !empty($data['current_load']) || 
                !empty($data['status'])) {
                
                $machine = Machine::find($machineId);
                
                MachineLog::create([
                    'machine_id' => $machineId,
                    'unit_id' => $machine->unit_id,
                    'capable_power' => $data['capable_power'] ?? null,
                    'supply_power' => $data['supply_power'] ?? null,
                    'current_load' => $data['current_load'] ?? null,
                    'status' => $data['status'] ?? null,
                    'input_time' => $inputTime,
                ]);

                // Update the latest status in machines table
                $machine->update([
                    'capable_power' => $data['capable_power'] ?? null,
                    'supply_power' => $data['supply_power'] ?? null,
                    'current_load' => $data['current_load'] ?? null,
                    'status' => $data['status'] ?? null,
                    'last_update' => $inputTime,
                ]);
            }
        }

        return redirect()->route('laporan-kesiapan-kit.index')
                        ->with('success', 'Data kesiapan dan HOP berhasil disimpan');
    }

    public function exportPDF()
    {
        $units = Unit::with(['machines' => function($query) {
            $query->with(['logs' => function($query) {
                $query->latest('input_time')->limit(1);
            }]);
        }])->get();

        $pdf = PDF::loadView('laporan-kesiapan-kit.pdf', compact('units'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('laporan-kesiapan-kit-'.now()->format('Y-m-d').'.pdf');
    }

    public function getByInputTime(Request $request)
    {
        $query = MachineLog::with(['machine', 'unit']);
        
        if ($request->filled('input_time')) {
            $query->whereTime('input_time', $request->input_time);
        }
        
        $logs = $query->latest('input_time')
                      ->get()
                      ->groupBy('machine_id')
                      ->map(function($machineLogs) {
                          return $machineLogs->first();
                      })
                      ->values();
        
        return response()->json($logs);
    }

    public function getLastData(Request $request)
    {
        try {
            Log::info('getLastData called with input time: ' . $request->input_time);

            // Get latest machine logs
            $machineLogs = MachineLog::with('machine')
                ->when($request->input_time, function($query, $time) {
                    return $query->whereTime('input_time', $time);
                })
                ->latest('input_time')
                ->get()
                ->groupBy('machine_id')
                ->map(function($logs) {
                    return $logs->first();
                });

            // Get latest HOP data
            $hopData = UnitHop::when($request->input_time, function($query, $time) {
                    return $query->whereTime('input_time', $time);
                })
                ->latest('input_time')
                ->get()
                ->groupBy('unit_id')
                ->map(function($hops) {
                    return $hops->first();
                });

            Log::info('Data retrieved', [
                'machines_count' => $machineLogs->count(),
                'hops_count' => $hopData->count()
            ]);

            return response()->json([
                'machines' => $machineLogs->values(),
                'hops' => $hopData->values(),
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getLastData: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}