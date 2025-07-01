<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('temperatura_corporals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestante_id');
            $table->float('valor');
            $table->dateTime('hora_lectura');
            $table->timestamps();

            $table->foreign('gestante_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temperatura_corporals');
    }
};
