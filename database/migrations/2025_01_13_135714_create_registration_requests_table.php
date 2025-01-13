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
        Schema::create('registration_requests', function (Blueprint $table) {
            $table->bigIncrements('rr_id');
            $table->string('rr_first_last_name')->comment('First and Last Name of user trying to register');
            $table->string('rr_email')->comment('Email of user trying to register');
            $table->tinyInteger('rr_status')->comment('0 = Pending, 1 = Approved, 2 = Rejected')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_request');
    }
};
