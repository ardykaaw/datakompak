<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelStock extends Model
{
    protected $fillable = [
        'record_date',
        'location',
        'fuel_type',
        'initial_stock',
        'received',
        'usage',
        'final_stock',
        'dead_stock',
        'daily_average_usage',
        'hop'
    ];

    protected $casts = [
        'record_date' => 'date',
        'initial_stock' => 'decimal:3',
        'received' => 'decimal:3',
        'usage' => 'decimal:3',
        'final_stock' => 'decimal:3',
        'dead_stock' => 'decimal:3',
        'daily_average_usage' => 'decimal:3',
        'hop' => 'decimal:3'
    ];

    /**
     * Get monthly summary for specific location and fuel type
     */
    public static function getMonthlyStats(int $year, int $month, string $location, string $fuelType)
    {
        return self::query()
            ->whereYear('record_date', $year)
            ->whereMonth('record_date', $month)
            ->where('location', $location)
            ->where('fuel_type', $fuelType)
            ->orderBy('record_date')
            ->get()
            ->pipe(function ($records) {
                return [
                    'total_received' => $records->sum('received'),
                    'total_usage' => $records->sum('usage'),
                    'average_daily_usage' => $records->avg('daily_average_usage'),
                    'final_stock' => $records->last()?->final_stock ?? 0,
                    'dead_stock' => $records->last()?->dead_stock ?? 0,
                    'current_hop' => $records->last()?->hop ?? 0,
                ];
            });
    }
} 