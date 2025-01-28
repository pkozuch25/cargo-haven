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
        Schema::create('disposition_units', function (Blueprint $table) {
            $table->bigIncrements('disu_id');
            $table->unsignedBigInteger('disu_dis_id');
            $table->foreign('disu_dis_id')->references('dispositions')->on('dis_id');
            $table->unsignedTinyInteger('disu_status')->nullable()->default(0);
            $table->string('disu_car_number', 20)->nullable();
            $table->string('disu_carriage_number', 20)->nullable();
            $table->string('disu_container_number', 15)->nullable();
            $table->unsignedMediumInteger('disu_container_net_weight')->nullable();
            $table->unsignedMediumInteger('disu_container_gross_weight')->nullable();
            $table->unsignedMediumInteger('disu_container_tare_weight')->nullable();
            $table->text('disu_notes')->nullable();
            $table->string('disu_driver', 100)->nullable();
            $table->unsignedBigInteger('disu_cardunit_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposition_unit');
    }
};
