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
        Schema::create('bookingStatus', function (Blueprint $table) {
            
            // Should Be registered when user decides to book unit
            $table->id();
            
            $table->string('date_booked');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_booked')->default(false);
            $table->string('number_of_days_booked');
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
        //
    }
};
