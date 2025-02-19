<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UnitHop extends Model
{
    use HasFactory;

    protected $table = 'unit_hops';

    protected $fillable = [
        'unit_id',
        'hop_value',
        'input_time'
    ];

    protected $casts = [
        'hop_value' => 'decimal:2',
        'input_time' => 'datetime'
    ];

    /**
     * Get the unit that owns the HOP record.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the latest HOP value for a specific unit
     */
    public static function getLatestHop($unitId, $inputTime = null)
    {
        $query = static::where('unit_id', $unitId);
        
        if ($inputTime) {
            $filterTime = Carbon::createFromFormat('H:i', $inputTime)->format('H:i:s');
            $query->whereRaw('TIME(input_time) = ?', [$filterTime]);
        }
        
        return $query->latest('input_time')->first();
    }
} 