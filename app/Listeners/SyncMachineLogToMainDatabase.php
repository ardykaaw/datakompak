<?php

namespace App\Listeners;

use App\Events\MachineLogCreated;
use App\Models\MachineLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncMachineLogToMainDatabase
{
    public function handle(MachineLogCreated $event)
    {
        try {
            // Jika data berasal dari database utama, tidak perlu sync
            if ($event->sourceConnection === 'mysql') {
                return;
            }

            // Buat instance baru MachineLog dengan koneksi ke database utama
            $mainDbLog = new MachineLog();
            $mainDbLog->setConnection('mysql');

            // Copy semua atribut
            $mainDbLog->machine_id = $event->machineLog->machine_id;
            $mainDbLog->unit_id = $event->machineLog->unit_id;
            $mainDbLog->capable_power = $event->machineLog->capable_power;
            $mainDbLog->supply_power = $event->machineLog->supply_power;
            $mainDbLog->current_load = $event->machineLog->current_load;
            $mainDbLog->status = $event->machineLog->status;
            $mainDbLog->input_time = $event->machineLog->input_time;

            // Simpan ke database utama
            $mainDbLog->save();

            Log::info('MachineLog berhasil disinkronkan ke database utama', [
                'id' => $mainDbLog->id,
                'source_connection' => $event->sourceConnection
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyinkronkan MachineLog ke database utama', [
                'error' => $e->getMessage(),
                'source_connection' => $event->sourceConnection
            ]);
        }
    }
} 