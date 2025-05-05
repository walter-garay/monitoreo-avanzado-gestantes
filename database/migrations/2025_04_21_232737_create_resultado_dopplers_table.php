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
        Schema::create('resultado_dopplers', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_examen');
            $table->float('indice_pulsatilidad');
            $table->float('indice_resistencia');

            $table->foreignId('gestante_id')->constrained('users')->onDelete('cascade');


            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultado_dopplers');
    }
};
