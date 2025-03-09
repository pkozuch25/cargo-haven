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
        Schema::create('storage_yards', function (Blueprint $table) {
            $table->bigIncrements('sy_id');
            $table->string('sy_name');
            $table->string('sy_name_short', 6);
            $table->integer('sy_length');
            $table->integer('sy_width');
            $table->integer('sy_cell_count');
            $table->integer('sy_row_count');
            $table->integer('sy_height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_yards');
    }
};
