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
        Schema::create('practitioners', function (Blueprint $table) {
            $table->id();
            $table->json('identifier');
            $table->boolean('active');
            $table->json('name');
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthDate')->nullable();
            $table->json('photo')->nullable();
            $table->json('quantification')->nullable();
            $table->json('communication')->nullable();
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
        Schema::dropIfExists('practitioners');
    }
};
