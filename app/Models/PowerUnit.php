<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PowerUnit extends Model
{
    protected $fillable = [
        'name',
        'installed_power',
        'silm_power',
        'supply_power',
        'status',
        'notes'
    ];

    /**
     * Get all daily records for this unit
     */
    public function dailyRecords(): HasMany
    {
        return $this->hasMany(DailyUnitRecord::class);
    }

    /**
     * Get monthly records for this unit
     */
    public function getMonthlyRecords(int $year, int $month)
    {
        return $this->dailyRecords()
            ->whereYear('record_date', $year)
            ->whereMonth('record_date', $month)
            ->orderBy('record_date')
            ->get();
    }

    /**
     * Calculate monthly totals and averages
     */
    public function getMonthlyStats(int $year, int $month)
    {
        $records = $this->getMonthlyRecords($year, $month);
        
        return [
            'total_gross_production' => $records->sum('gross_production'),
            'total_net_production' => $records->sum('net_production'),
            'average_eaf' => $records->avg('eaf'),
            'average_sof' => $records->avg('sof'),
            'total_operating_hours' => $records->sum('operating_hours'),
            'total_fuel_consumption' => $records->sum('total_fuel'),
            'average_sfc' => $records->avg('sfc'),
            'total_trips' => $records->sum('machine_trips') + $records->sum('electrical_trips'),
        ];
    }
} 