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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->string('status')->nullable();
            $table->json('operationalStatus')->nullable();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('description')->nullable();
            $table->json('mode')->nullable();
            $table->json('type')->nullable();
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->json('physicalType')->nullable();
            $table->json('position')->nullable();
            $table->json('managingOrganization')->nullable();
            $table->json('partOf')->nullable();
            $table->json('hoursOfOperation')->nullable();
            $table->json('availabilityExceptions')->nullable();
            $table->json('endpoint')->nullable();
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
        Schema::dropIfExists('locations');
    }
};
