<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('razon_social', 300)->nullable();
            $table->string('direccion', 300)->nullable();
            $table->string('comuna', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('numero_cuenta', 100)->nullable();
            $table->string('tipo_cuenta', 100)->nullable();
            $table->string('banco', 100)->nullable();
            $table->string('nombre_titular', 300)->nullable();
            $table->string('rut_titular', 20)->nullable();
            $table->string('correo', 255)->nullable();
            $table->string('certificado_bancario', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn([
                'razon_social', 'direccion', 'comuna', 'region', 'telefono',
                'numero_cuenta', 'tipo_cuenta', 'banco', 'nombre_titular', 
                'rut_titular', 'correo', 'certificado_bancario'
            ]);
        });
    }
};
