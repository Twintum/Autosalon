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
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->integer('price');
            $table->string('photo');
            $table->unsignedBigInteger('mark_id');
            $table->enum('transmission', ['mechanic', 'automatic', 'robot']);
            $table->enum('drive', ['FWD', 'RWD', 'AWD']);
            $table->integer('fuel_tank');
            $table->string('color');
            $table->integer('mileage');
            $table->integer('discount');
            $table->string('year');
            $table->timestamps();

            $table->foreign('mark_id')->references('id')->on('marks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
