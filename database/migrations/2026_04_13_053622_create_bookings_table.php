<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('staff_id');
            $table->integer('staff_service_id');
            $table->string('amount');
            $table->date('booking_date');
            $table->string('booking_time');
            $table->string('booking_timestamp');
            $table->string('timestamp');
            $table->enum('reschedule', ['No', 'Yes'])->default('No');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
