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
        Schema::create('dispositivo_wearables', function (Blueprint $table) {
            $table->id();
            $table->string('numero_serie');
            $table->string('modelo');
            $table->timestamp('asignado_en')->nullable();

            $table->foreignId('gestante_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivo_wearables');
    }
};
