<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cecos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 200);
            $table->string('tipo', 50); // Interno, Unidad de Negocio, Externo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cecos');
    }
};
