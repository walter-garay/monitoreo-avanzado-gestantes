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
        Schema::create('evaluacion_riesgos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_evaluacion');
            $table->string('algoritmo', 100);
            $table->float('puntaje');
            $table->enum('resultado', ['bajo', 'medio', 'alto']);
            $table->text('parametros_json');

            $table->foreignId('gestante_id')->constrained('users')->onDelete('cascade'); 

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion_riesgos');
    }
};
