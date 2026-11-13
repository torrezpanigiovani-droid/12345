<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $t->string('placa', 15)->unique();
            $t->string('marca', 50)->nullable();
            $t->string('modelo', 50)->nullable();
            $t->string('color', 30)->nullable();
            $t->enum('tipo', ['auto', 'moto', 'camioneta'])->default('auto');
            $t->boolean('estado')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
