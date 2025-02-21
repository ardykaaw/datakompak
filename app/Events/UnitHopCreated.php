<?php

namespace App\Events;

use App\Models\UnitHop;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitHopCreated
{
    use Dispatchable, SerializesModels;

    public $unitHop;
    public $sourceConnection;

    public function __construct(UnitHop $unitHop, string $sourceConnection)
    {
        $this->unitHop = $unitHop;
        $this->sourceConnection = $sourceConnection;
    }
} 