<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cambiar todas las solicitudes con estado "Enviada" a "Solicitada"
        DB::table('oc_solicitudes')
            ->where('estado', 'Enviada')
            ->update(['estado' => 'Solicitada']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En caso de revertir, cambiar de vuelta a "Enviada"
        // Nota: No se puede identificar cuáles fueron originalmente "Enviada"
        // así que esta reversión solo podría ser manual
    }
};
