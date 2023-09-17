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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('merchant_id');
            $table->string('booking_date')->comment('date format: Y-m-d');
            $table->string('booking_time')->comment('time format: H:i:s');
            $table->string('booking_status')->default('pending')->comment('pending, accepted, rejected, completed');
            $table->text('method_of_identity')->nullable();
            $table->text('identity_image')->nullable();
            $table->text('identity_number')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
