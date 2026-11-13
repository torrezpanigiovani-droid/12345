<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 120);
            $t->string('documento', 30)->unique();
            $t->string('telefono', 30)->nullable();
            $t->string('email', 120)->nullable();
            $t->string('direccion', 200)->nullable();
            $t->enum('tipo', ['habitual', 'abonado'])->default('habitual');
            $t->string('convenio', 150)->nullable();
            $t->date('fecha_inicio')->nullable();
            $t->date('fecha_fin')->nullable();
            $t->boolean('estado')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
