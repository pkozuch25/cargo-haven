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
        Schema::create('dispositions', function (Blueprint $table) {
            $table->bigIncrements('dis_id');
            $table->unsignedTinyInteger('dis_status')->default(0)->nullable();
            $table->unsignedBigInteger('dis_created_by_id')->nullable();
            $table->unsignedBigInteger('dis_relation_to')->nullable();
            $table->unsignedBigInteger('dis_relation_from')->nullable();
            $table->string('dis_number')->nullable();
            $table->text('dis_notes')->nullable();
            $table->dateTime('dis_suggested_date')->nullable();
            $table->dateTime('dis_start_date')->nullable();
            $table->dateTime('dis_completion_date')->nullable();
            $table->dateTime('dis_cancel_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};
