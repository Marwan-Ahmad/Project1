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
        Schema::create('expert', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->string('location');
            $table->string('descreption');
            $table->string('photo');
            $table->string('language');
            $table->string('Experience');
            $table->string('Eduction');
            $table->string('Rate');
            $table->foreignId('Country_id')->constrained('Country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert');
    }
};
