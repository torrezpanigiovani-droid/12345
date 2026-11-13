<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $t) {
            $t->id();
            $t->string('ticket', 30)->unique();
            $t->foreignId('vehiculo_id')->constrained('vehiculos');
            $t->foreignId('celda_id')->constrained('celdas');
            $t->foreignId('user_id')->constrained('users');          // operador que registró la entrada
            $t->foreignId('tarifa_id')->nullable()->constrained('tarifas')->nullOnDelete();
            $t->dateTime('entrada_at');
            $t->dateTime('salida_at')->nullable();
            $t->unsignedInteger('minutos')->nullable();
            $t->decimal('monto', 10, 2)->default(0);
            $t->enum('estado', ['activo', 'finalizado'])->default('activo');
            $t->timestamps();
            $t->index(['estado', 'entrada_at']);
        });

        Schema::create('pagos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('movimiento_id')->constrained('movimientos');
            $t->foreignId('user_id')->constrained('users');          // cajero
            $t->decimal('monto', 10, 2);
            $t->enum('metodo', ['efectivo', 'qr']);
            $t->string('referencia', 80)->nullable();
            $t->dateTime('pagado_at');
            $t->boolean('estado')->default(true);
            $t->timestamps();
            $t->index('pagado_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('movimientos');
    }
};
