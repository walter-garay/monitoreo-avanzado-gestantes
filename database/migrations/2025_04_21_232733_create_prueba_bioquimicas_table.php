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
        Schema::create('prueba_bioquimicas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_prueba');
            $table->float('papp_a');
            $table->float('plgf');
            $table->float('sflt1');
            $table->float('relacion_sflt1_plgf');
            $table->timestamps();

            $table->foreignId('gestante_id')->constrained('users')->onDelete('cascade'); // Relación con la tabla users
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prueba_bioquimicas');
    }
};
