<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oc_subidas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_oc', 50)->unique();
            $table->string('ceco', 50);
            $table->string('estado', 20);
            $table->decimal('monto', 14, 2);
            $table->date('fecha_envio');
            $table->string('archivo_path');
            $table->string('archivo_nombre');
            $table->string('enviado_a_email');
            $table->string('proveedor_email');
            $table->string('token_envio', 36)->unique();
            $table->string('token_proveedor', 36)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oc_subidas');
    }
};
