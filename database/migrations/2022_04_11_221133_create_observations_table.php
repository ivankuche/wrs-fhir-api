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
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('partOf')->nullable();
            $table->string("status");
            $table->json('category')->nullable();
            $table->json('code');
            $table->json('subject')->nullable();
            $table->json('focus')->nullable();
            $table->json('encounter')->nullable();
            $table->dateTime('effectiveDateTime')->nullable();
            $table->json('effectivePeriod')->nullable();
            $table->json('effectiveTiming')->nullable();
            $table->dateTime('effectiveInstant')->nullable();
            $table->dateTime('issued')->nullable();
            $table->json('performer')->nullable();
            $table->json('valueQuantity')->nullable();
            $table->json('valueCodeableConcept')->nullable();
            $table->string("valueString")->nullable();
            $table->boolean("valueBoolean")->nullable();
            $table->integer("valueInteger")->nullable();
            $table->json('valueRange')->nullable();
            $table->json('valueRatio')->nullable();
            $table->json('valueSampledData')->nullable();
            $table->time('valueTime')->nullable();
            $table->dateTime('valueDateTime')->nullable();
            $table->json('valuePeriod')->nullable();
            $table->json('dataAbsentReason')->nullable();
            $table->json('interpretation')->nullable();
            $table->json('note')->nullable();
            $table->json('bodySite')->nullable();
            $table->json('method')->nullable();
            $table->json('specimen')->nullable();
            $table->json('device')->nullable();
            $table->json('referenceRange')->nullable();
            $table->json('hasMember')->nullable();
            $table->json('derivedFrom')->nullable();
            $table->json('component')->nullable();
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
        Schema::dropIfExists('observations');
    }
};
