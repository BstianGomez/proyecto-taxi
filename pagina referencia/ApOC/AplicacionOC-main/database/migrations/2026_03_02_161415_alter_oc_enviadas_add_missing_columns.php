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
        if (!Schema::hasColumn('oc_enviadas', 'oc_solicitud_id')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->unsignedBigInteger('oc_solicitud_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'numero_oc')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->string('numero_oc')->nullable()->after('oc_solicitud_id');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'ceco')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->string('ceco')->nullable()->after('numero_oc');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'tipo_solicitud')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->string('tipo_solicitud')->nullable()->after('ceco');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'proveedor')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->string('proveedor')->nullable()->after('tipo_solicitud');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'email_proveedor')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->string('email_proveedor')->nullable()->after('proveedor');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'rut')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->string('rut')->nullable()->after('email_proveedor');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'descripcion')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->text('descripcion')->nullable()->after('rut');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'cantidad')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->integer('cantidad')->nullable()->after('descripcion');
            });
        }

        if (!Schema::hasColumn('oc_enviadas', 'monto')) {
            Schema::table('oc_enviadas', function (Blueprint $table) {
                $table->decimal('monto', 12, 2)->nullable()->after('cantidad');
            });
        }

        if (Schema::hasColumn('oc_enviadas', 'oc_solicitud_id') && Schema::hasTable('oc_solicitudes')) {
            $exists = DB::selectOne("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'oc_enviadas' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = 'oc_enviadas_oc_solicitud_id_foreign'");
            if (!$exists) {
                Schema::table('oc_enviadas', function (Blueprint $table) {
                    $table->foreign('oc_solicitud_id')
                        ->references('id')
                        ->on('oc_solicitudes')
                        ->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oc_enviadas', function (Blueprint $table) {
            if (Schema::hasColumn('oc_enviadas', 'oc_solicitud_id')) {
                try {
                    $table->dropForeign(['oc_solicitud_id']);
                } catch (\Throwable $e) {
                }
            }
        });

        $columns = ['monto', 'cantidad', 'descripcion', 'rut', 'email_proveedor', 'proveedor', 'tipo_solicitud', 'ceco', 'numero_oc', 'oc_solicitud_id'];
        foreach ($columns as $column) {
            if (Schema::hasColumn('oc_enviadas', $column)) {
                Schema::table('oc_enviadas', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
