<?php

namespace App\Events;

use App\Models\MachineLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MachineLogCreated
{
    use Dispatchable, SerializesModels;

    public $machineLog;
    public $sourceConnection;

    public function __construct(MachineLog $machineLog, string $sourceConnection)
    {
        $this->machineLog = $machineLog;
        $this->sourceConnection = $sourceConnection;
    }
} 