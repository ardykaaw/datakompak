<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkhtisarHarian extends Model
{
    use HasFactory;

    protected $table = 'data'; // Jika nama tabel tetap 'data'

    protected $fillable = [
        'unit_id',
        'machine_id',
        'installed_power',
        'dmn_power',
        'capable_power',
        'peak_load',
        'off_peak_load',
        'gross_production',
        'net_production',
        'operating_hours',
        'standby_hours',
        'planned_outage',
        'maintenance_outage',
        'forced_outage',
        'aux_power',
        'transformer_losses',
        'usage_percentage',
        'period_hours',
        'peak_load_day',
        'peak_load_night',
        'kit_ratio',
        'trip_machine',
        'trip_electrical',
        'efdh',
        'epdh',
        'eudh',
        'esdh',
        'eaf',             // Kinerja Pembangkit - EAF
        'sof',             // Kinerja Pembangkit - SOF
        'efor',            // Kinerja Pembangkit - EFOR
        'sdof',            // Kinerja Pembangkit - SdOF
        'ncf',             // Capability Factor - NCF
        'trip_non_omc',
        'nof',              // Nett Operating Factor
        'jsi',              // JSI
        'hsd_fuel',         // HSD Fuel Usage
        'b35_fuel',         // B35 Fuel Usage
        'mfo_fuel',         // MFO Fuel Usage
        'total_fuel',       // Total Fuel Usage
        'water_usage',      // Water Usage
        'meditran_oil',     // Meditran SX 15W/40 CH-4
        'salyx_420',        // Salyx 420
        'salyx_430',        // Salyx 430
        'travolube_a',      // TravoLube A
        'turbolube_46',     // Turbolube 46
        'turbolube_68',     // Turbolube 68
        'total_oil',        // Total Pelumas
        'sfc_scc',          // SFC/SCC
        'nphr',             // TARA KALOR/NPHR
        'slc',              // SLC
        'notes',            // Keterangan
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}