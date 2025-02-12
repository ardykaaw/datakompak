<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class IkhtisarHarianController extends Controller
{
    public function index()
    {
        $recentData = Data::latest()->take(5)->get();
        
        return view('ikhtisar-harian', compact('recentData'));
    }
} 