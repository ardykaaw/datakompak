<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oil_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('record_date');
            $table->string('location');                    // Lokasi
            $table->string('oil_type');                    // Jenis Pelumas
            $table->decimal('initial_stock', 10, 3);       // Stock Awal
            $table->decimal('received', 10, 3);            // Penerimaan
            $table->decimal('usage', 10, 3);              // Pemakaian
            $table->decimal('tug8_usage', 10, 3);         // TUG 8
            $table->decimal('final_stock', 10, 3);        // Stock Akhir
            $table->timestamps();
            
            // Unique constraint untuk memastikan tidak ada duplikasi record
            $table->unique(['record_date', 'location', 'oil_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oil_stocks');
    }
}; 