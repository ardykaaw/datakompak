<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Unit;
use Carbon\Carbon;


class IkhtisarHarianController extends Controller
{
    public function index()
    {
        // Ambil semua unit
        $units = Unit::all();
        
        // Ambil data hari ini
        $todayData = Data::whereDate('created_at', Carbon::today())->get();
        
        // Ambil data terbaru
        $recentData = Data::latest()->take(5)->get();
        
        return view('ikhtisar-harian', compact('units', 'todayData', 'recentData'));
    }

    public function store(Request $request)
    {
        // Validation and storing logic will go here
        return back()->with('success', 'Data berhasil disimpan');
    }
} 