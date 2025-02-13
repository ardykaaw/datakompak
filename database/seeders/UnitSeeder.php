<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            'PLTU MORAMO ( Subsistem Kendari )',
            'PLTD Wua Wua',
            'PLTD Poasia',
            'PLTD Poasia Containerized',
            'PLTD Kolaka',
            'PLTD Lanipa Nipa',
            'PLTD Ladumpi',
            'PLTM Sabilambo',
            'PLTM Mikuasi',
            'PLTD Bau Bau',
            'PLTD Pasarwajo',
            'PLTM Winning',
            'PLTD Raha',
            'PLTD WANGI-WANGI ( SIstem Isolated Wangi-Wangi )',
            'PLTD LANGARA ( Isolated Sistem Langara )',
            'PLTD EREKE ( Sistem Isolated Ereke )',
            'PLTMG KENDARI ( Subsistem Kendari )',
            'PLTU BARUTA ( Sistem Bau-Bau )',
            'PLTMG BAU-BAU ( Sistem Bau-Bau )',
            'UP KENDARI',
            'PLTM RONGI',
        ];

        foreach ($units as $unit) {
            Unit::create(['name' => $unit]);
        }
    }
} 