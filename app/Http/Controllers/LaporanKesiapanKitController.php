<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Machine;
use Illuminate\Http\Request;

class LaporanKesiapanKitController extends Controller
{
    public function index()
    {
        $units = Unit::with('machines')->get();
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
            'data' => 'required|array',
            'data.*.capable_power' => 'required|numeric',
            'data.*.supply_power' => 'required|numeric',
            'data.*.current_load' => 'required|numeric',
            'data.*.status' => 'required|in:OPS,RSH,FO,MO,PO',
        ]);

        // Store the data for each machine
        foreach ($request->data as $machineId => $data) {
            Machine::where('id', $machineId)->update([
                'capable_power' => $data['capable_power'],
                'supply_power' => $data['supply_power'],
                'current_load' => $data['current_load'],
                'status' => $data['status'],
                'last_update' => $request->input_time,
            ]);
        }

        return redirect()->route('laporan-kesiapan-kit.index')
                        ->with('success', 'Data kesiapan berhasil disimpan');
    }
}
