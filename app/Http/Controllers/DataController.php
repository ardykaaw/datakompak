<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required|string|max:255',
            'value' => 'required|numeric',
        ]);

        Data::create($validated);

        return redirect()->back()->with('success', 'Data saved successfully');
    }
}
