<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use Carbon\Carbon;

class IkhtisarHarianController extends Controller
{
    public function index()
    {
        // Ambil data hari ini
        $todayData = Data::whereDate('created_at', Carbon::today())->get();
        
        // Ambil data terbaru
        $recentData = Data::latest()->take(5)->get();
        
        return view('ikhtisar-harian', compact('todayData', 'recentData'));
    }
} 