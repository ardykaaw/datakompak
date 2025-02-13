<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyUnitRecord extends Model
{
    protected $fillable = [
        'power_unit_id',
        'record_date',
        'peak_load_day',
        'peak_load_night',
        'power_ratio',
        'gross_production',
        'net_production',
        'aux_power',
        'transformer_losses',
        'usage_percentage',
        'period_hours',
        'operating_hours',
        'planned_maintenance',
        'maintenance_outage',
        'forced_outage',
        'standby_hours',
        'machine_trips',
        'electrical_trips',
        'efdh',
        'epdh',
        'eudh',
        'esdh',
        'eaf',
        'sof',
        'efor',
        'sdof',
        'capability_factor',
        'net_operating_factor',
        'hsd',
        'mfo',
        'total_fuel',
        'lube_oil_consumption',
        'sfc',
        'nphr',
        'slc',
        'operational_status',
        'notes'
    ];

    protected $casts = [
        'record_date' => 'date',
        'peak_load_day' => 'decimal:3',
        'peak_load_night' => 'decimal:3',
        'power_ratio' => 'decimal:3',
        'gross_production' => 'decimal:3',
        'net_production' => 'decimal:3',
        'aux_power' => 'decimal:3',
        'transformer_losses' => 'decimal:3',
        'usage_percentage' => 'decimal:3',
        'period_hours' => 'decimal:3',
        'operating_hours' => 'decimal:3',
        'planned_maintenance' => 'decimal:3',
        'maintenance_outage' => 'decimal:3',
        'forced_outage' => 'decimal:3',
        'standby_hours' => 'decimal:3',
        'efdh' => 'decimal:3',
        'epdh' => 'decimal:3',
        'eudh' => 'decimal:3',
        'esdh' => 'decimal:3',
        'eaf' => 'decimal:3',
        'sof' => 'decimal:3',
        'efor' => 'decimal:3',
        'capability_factor' => 'decimal:3',
        'net_operating_factor' => 'decimal:3',
        'hsd' => 'decimal:3',
        'mfo' => 'decimal:3',
        'total_fuel' => 'decimal:3',
        'lube_oil_consumption' => 'decimal:3',
        'sfc' => 'decimal:6',
        'nphr' => 'decimal:3',
        'slc' => 'decimal:3'
    ];

    /**
     * Get the power unit that owns this record
     */
    public function powerUnit(): BelongsTo
    {
        return $this->belongsTo(PowerUnit::class);
    }
} 