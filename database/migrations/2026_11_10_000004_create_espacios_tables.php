<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pisos', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 60)->unique();
            $t->string('descripcion', 200)->nullable();
            $t->boolean('estado')->default(true);
            $t->timestamps();
        });

        Schema::create('sectores', function (Blueprint $t) {
            $t->id();
            $t->foreignId('piso_id')->constrained('pisos');
            $t->string('nombre', 60);
            $t->boolean('estado')->default(true);
            $t->timestamps();
            $t->unique(['piso_id', 'nombre']);
        });

        Schema::create('celdas', function (Blueprint $t) {
            $t->id();
            $t->foreignId('sector_id')->constrained('sectores');
            $t->string('codigo', 20)->unique();
            $t->enum('tipo_vehiculo', ['todos', 'auto', 'moto', 'camioneta'])->default('todos');
            $t->boolean('ocupada')->default(false);   // disponible / ocupada en tiempo real
            $t->boolean('estado')->default(true);     // activa / inactiva
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('celdas');
        Schema::dropIfExists('sectores');
        Schema::dropIfExists('pisos');
    }
};
