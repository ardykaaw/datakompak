<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        // Get today's data for each unit
        $todayData = Data::whereDate('created_at', today())->get();
        
        // Get recent data for the activity log
        $recentData = Data::latest()->take(5)->get();
        
        return view('ikhtisar-harian', [
            'todayData' => $todayData,
            'recentData' => $recentData
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required|string|max:255',
            'value' => 'required|numeric',
            'efficiency' => 'required|numeric|min:0|max:100',
            'operating_hours' => 'required|numeric|min:0|max:24',
            'notes' => 'nullable|string|max:1000'
        ]);

        Data::create($validated);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
