<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MachineLog;
use App\Models\UnitHop;
use Carbon\Carbon;

class CheckSyncStatus extends Command
{
    protected $signature = 'sync:check {--date= : Check specific date (Y-m-d)}';
    protected $description = 'Check synchronization status between unit and main database';

    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();

        $this->info("Checking sync status for date: " . $date->format('Y-m-d'));

        // Check MachineLog sync
        $this->checkModelSync(MachineLog::class, $date);
        
        // Check UnitHop sync
        $this->checkModelSync(UnitHop::class, $date);
    }

    private function checkModelSync($model, $date)
    {
        $modelName = class_basename($model);
        $this->info("\nChecking $modelName synchronization...");

        $mainCount = $model::on('mysql')
            ->whereDate('created_at', $date)
            ->count();

        $unitCount = $model::on(session('db_connection'))
            ->whereDate('created_at', $date)
            ->count();

        $this->table(
            ['Database', 'Record Count'],
            [
                ['Main (mysql)', $mainCount],
                ['Unit (' . session('db_connection') . ')', $unitCount],
            ]
        );

        if ($mainCount !== $unitCount) {
            $this->warn("Warning: Record count mismatch detected!");
        }
    }
} 