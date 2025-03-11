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
        Schema::create('deposits', function (Blueprint $table) {
            $table->bigIncrements('dep_id');
            $table->unsignedBigInteger('dep_sc_id')->nullable();
            $table->string('dep_number')->unique();
            $table->date('dep_arrival_date');
            $table->date('dep_departure_date')->nullable();
            $table->unsignedBigInteger('dep_arrival_card_id');
            $table->unsignedBigInteger('dep_arrival_cardunit_id');
            $table->unsignedBigInteger('dep_departure_card_id')->nullable();
            $table->unsignedBigInteger('dep_departure_cardunit_id')->nullable();
            $table->unsignedBigInteger('dep_gross_weight')->nullable();
            $table->unsignedBigInteger('dep_tare_weight')->nullable();
            $table->unsignedBigInteger('dep_net_weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
