<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OilStock extends Model
{
    protected $fillable = [
        'record_date',
        'location',
        'oil_type',
        'initial_stock',
        'received',
        'usage',
        'tug8_usage',
        'final_stock'
    ];

    protected $casts = [
        'record_date' => 'date',
        'initial_stock' => 'decimal:3',
        'received' => 'decimal:3',
        'usage' => 'decimal:3',
        'tug8_usage' => 'decimal:3',
        'final_stock' => 'decimal:3'
    ];

    /**
     * Get monthly summary for specific location and oil type
     */
    public static function getMonthlyStats(int $year, int $month, string $location, string $oilType)
    {
        return self::query()
            ->whereYear('record_date', $year)
            ->whereMonth('record_date', $month)
            ->where('location', $location)
            ->where('oil_type', $oilType)
            ->orderBy('record_date')
            ->get()
            ->pipe(function ($records) {
                return [
                    'total_received' => $records->sum('received'),
                    'total_usage' => $records->sum('usage'),
                    'total_tug8_usage' => $records->sum('tug8_usage'),
                    'final_stock' => $records->last()?->final_stock ?? 0,
                ];
            });
    }
} 