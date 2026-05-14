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
        Schema::create('taxi_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('rut');
            $table->string('phone');
            $table->boolean('is_associated_ot');
            $table->string('project_number')->nullable();

            $table->string('start_address');
            $table->string('destination_address');
            $table->timestamp('request_time');
            $table->timestamp('estimated_arrival_time');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_requests');
    }
};
