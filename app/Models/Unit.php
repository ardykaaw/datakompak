<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function ikhtisarHarians()
    {
        return $this->hasMany(IkhtisarHarian::class);
    }

    /**
     * Get all HOP records for this unit
     */
    public function hops()
    {
        return $this->hasMany(UnitHop::class);
    }

    /**
     * Get the latest HOP record for this unit
     */
    public function latestHop()
    {
        return $this->hasOne(UnitHop::class)
            ->latest('input_time');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getConnectionName()
    {
        return session('db_connection', 'mysql');
    }
} 