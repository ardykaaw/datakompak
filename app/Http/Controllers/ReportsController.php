<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class ReportsController extends Controller
{
    public function index()
    {
        $monthlyData = Data::whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->get();
                          
        return view('reports', compact('monthlyData'));
    }
} 