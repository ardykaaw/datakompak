<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class DashboardController extends Controller
{
    public function index()
    {
        $recentData = Data::latest()->take(5)->get();
        return view('dashboard', compact('recentData'));
    }

    public function superAdmin()
    {
        $allData = Data::all();
        return view('super-admin', compact('allData'));
    }
}
