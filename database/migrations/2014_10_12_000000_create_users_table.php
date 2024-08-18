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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Add validation on front end
            $table->string('email')->unique();
            $table->string('phone_number')->default('0722222222');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->string('profile_photo')->default('Images\user\userProfile\profilePhoto.webp');
            $table->text('description')->default('The user is a dedicated and passionate home owner who has meticulously curated a selection of premium properties available for rent and sale. With a keen eye for detail and a commitment to providing exceptional living experiences, the user has become a trusted name in the real estate community. His properties are known for their modern amenities, prime locations, and impeccable upkeep.');
            $table->text('general_property_overview')->default('The user portfolio includes a diverse range of properties, from cozy apartments to spacious family homes. Each property is carefully maintained and equipped with all the necessary amenities to ensure comfort and convenience for tenants. Joshua prides himself on offering homes that are not just places to live, but environments where people can truly thrive.');
            $table->rememberToken();
            $table->timestamps();        
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};


