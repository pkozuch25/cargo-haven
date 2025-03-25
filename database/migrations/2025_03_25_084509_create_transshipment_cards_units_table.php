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
        Schema::create('transshipment_cards_units', function (Blueprint $table) {
            $table->bigIncrements('tcu_id');
            $table->unsignedBigInteger('tcu_tc_id');
            $table->unsignedBigInteger('tcu_operator_id');
            $table->unsignedBigInteger('tcu_disp_id');
            $table->string('tcu_container_number')->nullable();
            $table->string('tcu_yard_position')->nullable();
            $table->string('tcu_carriage_number_from')->nullable();
            $table->string('tcu_carriage_number_to')->nullable();
            $table->string('tcu_truck_number_from')->nullable();
            $table->string('tcu_truck_number_to')->nullable();
            $table->unsignedBigInteger('tcu_gross_weight')->nullable();
            $table->unsignedBigInteger('tcu_net_weight')->nullable();
            $table->unsignedBigInteger('tcu_tare_weight')->nullable();
            $table->text('tcu_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transshipment_cards_units');
    }
};
