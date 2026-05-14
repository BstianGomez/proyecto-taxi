<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razones_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('rut', 20);
            $table->string('cod_deudor', 50);
            $table->string('razon_social', 300);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razones_sociales');
    }
};
