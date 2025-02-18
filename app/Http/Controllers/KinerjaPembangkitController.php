<?php

namespace App\Http\Controllers;

use App\Models\IkhtisarHarian;
use Illuminate\Http\Request;

class KinerjaPembangkitController extends Controller
{
    public function index()
    {
        $data = IkhtisarHarian::select('eaf', 'sof', 'efor', 'sdof', 'ncf')
            ->get();
            
        return view('kinerja-pembangkit.index', compact('data'));
    }
} 