<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;

class UnitMesinController extends Controller
{
    public function index()
    {
        $machines = Machine::all();
        return view('unit-mesin.index', compact('machines'));
    }

    // ... other resource methods ...
} 