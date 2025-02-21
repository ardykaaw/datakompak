<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Events\UnitHopCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected static function booted()
    {
        static::created(function ($unitHop) {
            // Trigger event setelah data disimpan
            event(new UnitHopCreated($unitHop, $unitHop->getConnectionName()));
        });
    }

    /**
     * Get the unit that owns the HOP record.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the latest HOP value for a specific unit
     */
    public static function getLatestHop($unitId, $time = null)
    {
        $query = static::where('unit_id', $unitId);
        
        if ($time) {
            $query->whereTime('input_time', $time);
        }
        
        return $query->latest('input_time')->first();
    }

    // Override getConnectionName untuk dynamic connection
    public function getConnectionName()
    {
        return session('db_connection', parent::getConnectionName());
    }
} 