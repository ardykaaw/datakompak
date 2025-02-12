<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class AnalyticsController extends Controller
{
    public function index()
    {
        $data = Data::latest()->get();
        return view('analytics', compact('data'));
    }
} 