<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'specifications',
        'dmn',
        'dmp',
        'unit_id'
    ];

    protected $casts = [
        'dmn' => 'decimal:2',
        'dmp' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function ikhtisarHarians()
    {
        return $this->hasMany(IkhtisarHarian::class);
    }

    public function logs()
    {
        return $this->hasMany(MachineLog::class)->latest('input_time');
    }
} 