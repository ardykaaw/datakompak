<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\IkhtisarHarian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IkhtisarHarianExport;

class IkhtisarHarianViewController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::all();
        $query = IkhtisarHarian::with(['unit', 'machine']);

        // Filter berdasarkan unit
        if ($request->has('unit')) {
            $query->where('unit_id', $request->unit);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $data = $query->latest()->paginate(15);

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('ikhtisar-harian-view', compact('data', 'units'));
    }

    public function export(Request $request) 
    {
        $fileName = 'ikhtisar-harian-' . Carbon::now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new IkhtisarHarianExport(
            $request->unit,
            $request->start_date,
            $request->end_date
        ), $fileName);
    }
} 