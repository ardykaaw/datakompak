<?php

namespace App\Listeners;

use App\Events\UnitHopCreated;
use App\Models\UnitHop;
use Illuminate\Support\Facades\Log;

class SyncUnitHopToMainDatabase
{
    public function handle(UnitHopCreated $event)
    {
        try {
            // Jika data berasal dari database utama, tidak perlu sync
            if ($event->sourceConnection === 'mysql') {
                return;
            }

            // Buat instance baru UnitHop dengan koneksi ke database utama
            $mainDbHop = new UnitHop();
            $mainDbHop->setConnection('mysql');

            // Copy semua atribut
            $mainDbHop->unit_id = $event->unitHop->unit_id;
            $mainDbHop->hop_value = $event->unitHop->hop_value;
            $mainDbHop->input_time = $event->unitHop->input_time;

            // Simpan ke database utama
            $mainDbHop->save();

            Log::info('UnitHop berhasil disinkronkan ke database utama', [
                'id' => $mainDbHop->id,
                'source_connection' => $event->sourceConnection
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyinkronkan UnitHop ke database utama', [
                'error' => $e->getMessage(),
                'source_connection' => $event->sourceConnection
            ]);
        }
    }
} 