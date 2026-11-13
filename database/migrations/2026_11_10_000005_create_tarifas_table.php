<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas', function (Blueprint $t) {
            $t->id();
            $t->enum('tipo_vehiculo', ['auto', 'moto', 'camioneta']);
            $t->enum('modalidad', ['minuto', 'hora', 'fraccion']);
            $t->decimal('precio', 10, 2);
            $t->unsignedSmallInteger('fraccion_minutos')->default(15);
            $t->boolean('estado')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};
