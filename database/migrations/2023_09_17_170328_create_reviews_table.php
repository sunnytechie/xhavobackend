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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('merchant_id');
            $table->integer('rating');
            $table->string('comment');
            $table->integer('verified')->default(0);
            $table->integer('approved')->default(0);
            $table->integer('spam')->default(0);
            $table->integer('deleted')->default(0);
            $table->integer('blocked')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
