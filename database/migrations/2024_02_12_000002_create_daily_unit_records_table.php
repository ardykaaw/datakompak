<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_unit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('power_unit_id')->constrained()->onDelete('cascade');
            $table->date('record_date');
            
            // Beban Puncak
            $table->decimal('peak_load_day', 10, 3)->nullable();    // Beban Puncak Siang
            $table->decimal('peak_load_night', 10, 3)->nullable();  // Beban Puncak Malam
            $table->decimal('power_ratio', 10, 3)->nullable();      // Ratio Daya
            
            // Produksi
            $table->decimal('gross_production', 10, 3)->nullable(); // Produksi Bruto (kWh)
            $table->decimal('net_production', 10, 3)->nullable();   // Produksi Netto (kWh)
            $table->decimal('aux_power', 10, 3)->nullable();        // Pemakaian Sendiri Aux
            $table->decimal('transformer_losses', 10, 3)->nullable();// Susut Trafo
            $table->decimal('usage_percentage', 10, 3)->nullable(); // Persentase Pemakaian
            
            // Jam Operasi
            $table->decimal('period_hours', 10, 3)->nullable();     // Jam Periode
            $table->decimal('operating_hours', 10, 3)->nullable();  // OPR
            $table->decimal('planned_maintenance', 10, 3)->nullable();// HAR PO
            $table->decimal('maintenance_outage', 10, 3)->nullable();// HAR MO
            $table->decimal('forced_outage', 10, 3)->nullable();    // HAR FO
            $table->decimal('standby_hours', 10, 3)->nullable();    // STAND BY
            
            // Gangguan
            $table->integer('machine_trips')->nullable();           // Trip Non OMC MESIN
            $table->integer('electrical_trips')->nullable();        // Trip Non OMC LISTRIK
            $table->decimal('efdh', 10, 3)->nullable();            // EFDH
            $table->decimal('epdh', 10, 3)->nullable();            // EPDH
            $table->decimal('eudh', 10, 3)->nullable();            // EUDH
            $table->decimal('esdh', 10, 3)->nullable();            // ESDH
            
            // Kinerja
            $table->decimal('eaf', 10, 3)->nullable();             // EAF
            $table->decimal('sof', 10, 3)->nullable();             // SOF
            $table->decimal('efor', 10, 3)->nullable();            // EFOR
            $table->integer('sdof')->nullable();                   // SdOF
            $table->decimal('capability_factor', 10, 3)->nullable();// CF
            $table->decimal('net_operating_factor', 10, 3)->nullable();// NOF
            
            // Bahan Bakar
            $table->decimal('hsd', 10, 3)->nullable();             // HSD
            $table->decimal('mfo', 10, 3)->nullable();             // MFO
            $table->decimal('total_fuel', 10, 3)->nullable();      // Total BBM
            
            // Pelumas
            $table->decimal('lube_oil_consumption', 10, 3)->nullable(); // Total Pelumas
            
            // Efisiensi
            $table->decimal('sfc', 10, 6)->nullable();             // SFC
            $table->decimal('nphr', 10, 3)->nullable();            // NPHR
            $table->decimal('slc', 10, 3)->nullable();             // SLC
            
            // Status dan Catatan
            $table->string('operational_status')->nullable();       // Status Operasi
            $table->text('notes')->nullable();                     // Catatan
            
            $table->timestamps();
            
            // Unique constraint untuk memastikan tidak ada duplikasi record per unit per hari
            $table->unique(['power_unit_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_unit_records');
    }
}; 