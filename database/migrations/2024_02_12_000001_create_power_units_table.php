<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('power_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Nama unit (e.g., MAK 8M 453 AK #1)
            $table->decimal('installed_power', 10, 3); // Daya Terpasang
            $table->decimal('silm_power', 10, 3);      // Daya Mampu SILM
            $table->decimal('supply_power', 10, 3);    // Daya Mampu Pasok
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('power_units');
    }
}; 