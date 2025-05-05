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
        Schema::create('notificacions', function (Blueprint $table) {
            $table->id();
            $table->enum('canal', ['web', 'mobile']);
            $table->text('mensaje');
            $table->enum('estado', ['enviado', 'entregado', 'leido']);

            $table->foreignId('gestante_id')->constrained('users')->onDelete('cascade'); 

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacions');
    }
};
