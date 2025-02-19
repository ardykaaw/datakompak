<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineLog extends Model
{
    protected $fillable = [
        'machine_id',
        'unit_id',
        'capable_power',
        'supply_power',
        'current_load',
        'status',
        'input_time'
    ];

    protected $casts = [
        'input_time' => 'datetime',
        'capable_power' => 'decimal:2',
        'supply_power' => 'decimal:2',
        'current_load' => 'decimal:2',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
} 