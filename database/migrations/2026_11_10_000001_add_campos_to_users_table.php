<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->enum('rol', ['administrador', 'operador', 'supervisor'])->default('operador')->after('password');
            $t->string('telefono', 30)->nullable()->after('rol');
            $t->boolean('estado')->default(true)->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['rol', 'telefono', 'estado']);
        });
    }
};
