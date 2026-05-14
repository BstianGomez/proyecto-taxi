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
        Schema::table('taxi_requests', function (Blueprint $table) {
            $table->foreignId('current_taxi_id')->nullable()->constrained('users')->onDelete('set null')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxi_requests', function (Blueprint $table) {
            $table->dropForeign(['current_taxi_id']);
            $table->dropColumn('current_taxi_id');
        });
    }
};
