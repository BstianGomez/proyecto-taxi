<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oc_solicitudes', function (Blueprint $table) {
            $table->string('estado_facturacion', 30)->default('No Facturado');
            $table->text('observacion_rechazo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('oc_solicitudes', function (Blueprint $table) {
            $table->dropColumn(['estado_facturacion', 'observacion_rechazo']);
        });
    }
};
