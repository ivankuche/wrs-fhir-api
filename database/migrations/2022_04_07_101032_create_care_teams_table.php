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
        Schema::create('care_teams', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->string('status')->nullable();
            $table->json('category')->nullable();
            $table->string('name')->nullable();
            $table->json('subject')->nullable();
            $table->json('encounter')->nullable();
            $table->json('period')->nullable();
            $table->json('participant')->nullable();
            $table->json('reasonCode')->nullable();
            $table->json('reasonReference')->nullable();
            $table->json('managingOrganization')->nullable();
            $table->json('telecom')->nullable();
            $table->json('note')->nullable();
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
        Schema::dropIfExists('care_teams');
    }
};
