<?php

namespace App\Models;

use App\Events\MachineLogCreated;
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

    protected static function booted()
    {
        static::created(function ($machineLog) {
            // Trigger event setelah data disimpan
            event(new MachineLogCreated($machineLog, $machineLog->getConnectionName()));
        });
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // Override getConnectionName untuk dynamic connection
    public function getConnectionName()
    {
        return session('db_connection', parent::getConnectionName());
    }
} 