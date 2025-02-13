<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['unit_id', 'name', 'code', 'specifications'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
} 