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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->json('identifier');
            $table->boolean('active');
            $table->json('name');
            $table->json('telecom')->nullable();
            $table->string('gender');
            $table->date('birthDate');
            $table->boolean('deceasedBoolean')->nullable();
            $table->date('deceasedDateTime')->nullable();
            $table->json('address')->nullable();
            $table->json('maritalStatus')->nullable();
            $table->json('communication')->nullable();
            $table->json('contact')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
