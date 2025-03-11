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
        Schema::create('storage_cells', function (Blueprint $table) {
            $table->bigIncrements('sc_id');
            $table->unsignedBigInteger('sc_yard_id');
            $table->foreign('sc_yard_id')->references('sy_id')->on('storage_yards');
            $table->string('sc_yard_name_short');
            $table->unsignedMediumInteger('sc_cell');
            $table->string('sc_row', 2);
            $table->unsignedTinyInteger('sc_height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_cells');
    }
};
