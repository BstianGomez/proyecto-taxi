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
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS oc_solicitud_id BIGINT UNSIGNED NULL AFTER id");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS numero_oc VARCHAR(255) NULL AFTER oc_solicitud_id");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS ceco VARCHAR(255) NULL AFTER numero_oc");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS tipo_solicitud VARCHAR(255) NULL AFTER ceco");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS proveedor VARCHAR(255) NULL AFTER tipo_solicitud");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS email_proveedor VARCHAR(255) NULL AFTER proveedor");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS rut VARCHAR(255) NULL AFTER email_proveedor");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER rut");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS cantidad INT NULL AFTER descripcion");
        DB::statement("ALTER TABLE oc_enviadas ADD COLUMN IF NOT EXISTS monto DECIMAL(12,2) NULL AFTER cantidad");

        try {
            DB::statement("ALTER TABLE oc_enviadas ADD CONSTRAINT oc_enviadas_oc_solicitud_id_foreign FOREIGN KEY (oc_solicitud_id) REFERENCES oc_solicitudes(id) ON DELETE CASCADE");
        } catch (\Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE oc_enviadas DROP FOREIGN KEY oc_enviadas_oc_solicitud_id_foreign");
        } catch (\Throwable $e) {
        }

        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS monto");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS cantidad");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS descripcion");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS rut");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS email_proveedor");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS proveedor");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS tipo_solicitud");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS ceco");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS numero_oc");
        DB::statement("ALTER TABLE oc_enviadas DROP COLUMN IF EXISTS oc_solicitud_id");
    }
};
