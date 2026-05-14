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
        Schema::table('oc_enviadas', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('monto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oc_enviadas', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }
};
