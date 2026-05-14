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
        Schema::create("oc_enviadas", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("oc_solicitud_id")->nullable();
            $table->string("numero_oc")->nullable();
            $table->string("ceco")->nullable();
            $table->string("tipo_solicitud")->nullable();
            $table->string("proveedor")->nullable();
            $table->string("email_proveedor")->nullable();
            $table->string("rut")->nullable();
            $table->text("descripcion")->nullable();
            $table->integer("cantidad")->nullable();
            $table->decimal("monto", 12, 2)->nullable();
            $table->timestamps();

            $table->foreign("oc_solicitud_id")
                ->references("id")
                ->on("oc_solicitudes")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("oc_enviadas");
    }
};
