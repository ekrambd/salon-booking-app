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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->foreignId('specialty_id')->nullable()->constrained('specialities')->onDelete('cascade');
            $table->foreignId('experience_id')->nullable()->constrained('experiences')->onDelete('cascade');
            $table->foreignId('working_time_range_id')->nullable()->constrained('working_time_ranges')->onDelete('cascade');
            $table->integer('slot_duration_minutes')->nullable()->default(15);
            $table->decimal('balance', 12, 2)->nullable()->default(0);
            $table->string('current_status', 191)->nullable()->default('Available');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('staff');
    }
};
