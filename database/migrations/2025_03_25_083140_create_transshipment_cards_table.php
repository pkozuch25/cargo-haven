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
        Schema::create('transshipment_cards', function (Blueprint $table) {
            $table->bigIncrements('tc_id');
            $table->string('tc_number')->nullable();
            $table->unsignedTinyInteger('tc_relation_from')->nullable();
            $table->unsignedTinyInteger('tc_relation_to')->nullable();
            $table->unsignedBigInteger('tc_created_by_user')->nullable();
            $table->unsignedBigInteger('tc_yard_id')->nullable();
            $table->unsignedTinyInteger('tc_status')->nullable();
            $table->text('tc_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transshipment_cards');
    }
};
