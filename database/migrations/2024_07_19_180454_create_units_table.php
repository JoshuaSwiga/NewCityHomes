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
    Schema::create('units', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('subtitle');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('userThatUploaded');
        $table->string('category'); 
        $table->boolean('is_booked')->default(false);
        $table->string('accomodation_information')->default('Accomodation Number: toEdit');
        $table->string('number_of_bedrooms')->default('Bed Room Number: toEdit');
        $table->string('number_of_bathrooms')->default('Bathroom Number: toEdit');
        $table->string('price_information')->default('Price: toEdit');
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
        Schema::dropIfExists('units');
    }
};
