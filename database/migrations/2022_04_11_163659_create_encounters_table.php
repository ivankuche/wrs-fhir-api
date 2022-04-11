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
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->string('status');
            $table->json('statusHistory')->nullable();
            $table->json('class');
            $table->json('classHistory')->nullable();
            $table->json('type')->nullable();
            $table->json('serviceType')->nullable();
            $table->json('priority')->nullable();
            $table->json('subject')->nullable();
            $table->json('episodeOfCare')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('participant')->nullable();
            $table->json('appointment')->nullable();
            $table->json('period')->nullable();
            $table->json('length')->nullable();
            $table->json('reasonCode')->nullable();
            $table->json('reasonReference')->nullable();
            $table->json('diagnosis')->nullable();
            $table->json('account')->nullable();
            $table->json('hospitalization')->nullable();
            $table->json('location')->nullable();
            $table->json('serviceProvider')->nullable();
            $table->json('partOf')->nullable();
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
        Schema::dropIfExists('encounters');
    }
};
