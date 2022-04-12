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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('instantiatesCanonical')->nullable();
            $table->json('instantiatesUri')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('partOf')->nullable();
            $table->string("status");
            $table->json('statusReason')->nullable();
            $table->json('category')->nullable();
            $table->json('code')->nullable();
            $table->json('subject');
            $table->json('encounter')->nullable();
            $table->dateTime('performedDateTime')->nullable();
            $table->json('performedPeriod')->nullable();
            $table->string("performedString")->nullable();
            $table->json('performedAge')->nullable();
            $table->json('performedRange')->nullable();
            $table->json('recorder')->nullable();
            $table->json('asserter')->nullable();
            $table->json('performer')->nullable();
            $table->json('location')->nullable();
            $table->json('reasonCode')->nullable();
            $table->json('reasonReference')->nullable();
            $table->json('bodySite')->nullable();
            $table->json('outcome')->nullable();
            $table->json('report')->nullable();
            $table->json('complication')->nullable();
            $table->json('complicationDetail')->nullable();
            $table->json('followUp')->nullable();
            $table->json('note')->nullable();
            $table->json('focalDevice')->nullable();
            $table->json('usedReference')->nullable();
            $table->json('usedCode')->nullable();
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
        Schema::dropIfExists('procedures');
    }
};
