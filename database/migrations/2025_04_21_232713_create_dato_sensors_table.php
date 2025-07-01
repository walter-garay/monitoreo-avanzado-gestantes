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
        Schema::create('dato_sensors', function (Blueprint $table) {
            $table->id();
            $table->timestamp('hora_lectura')->nullable();
            $table->text('dato');

            $table->foreignId('dispositivo_id')->constrained('dispositivo_wearables')->onDelete('cascade');
            $table->foreignId('tipo_sensor_id')->constrained('tipo_sensors')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dato_sensors');
    }
};
