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
        Schema::create('oc_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('ceco', 50);
            $table->string('tipo_solicitud', 20);
            $table->string('tipo_documento', 20);
            $table->string('estado', 20)->default('Solicitada');
            $table->string('rut', 20)->nullable();
            $table->string('proveedor', 200)->nullable();
            $table->string('descripcion', 300)->nullable();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('monto', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oc_solicitudes');
    }
};
