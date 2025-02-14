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
        'planned_outage',
        'maintenance_outage',
        'forced_outage'
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