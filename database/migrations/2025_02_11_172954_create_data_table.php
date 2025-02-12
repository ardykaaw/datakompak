<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->decimal('value', 10, 2); // Power output in MW
            $table->decimal('efficiency', 5, 2); // Efficiency percentage
            $table->decimal('operating_hours', 4, 2); // Operating hours
            $table->text('notes')->nullable(); // Optional notes
            $table->string('status')->default('operational'); // Unit status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
