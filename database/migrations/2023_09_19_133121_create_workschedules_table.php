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
        Schema::create('workschedules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('day')->comment('Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday');
            $table->string('start_time')->comment('24 hours format');
            $table->string('end_time')->comment('24 hours format');
            $table->string('status')->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workschedules');
    }
};
