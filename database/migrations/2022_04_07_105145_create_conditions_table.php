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
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('clinicalStatus')->nullable();
            $table->json('verificationStatus')->nullable();
            $table->json('category')->nullable();
            $table->json('severity')->nullable();
            $table->json('code')->nullable();
            $table->json('bodySite')->nullable();
            $table->json('subject');
            $table->json('encounter')->nullable();
            $table->dateTime('onsetDateTime')->nullable();
            $table->string('onsetAge')->nullable();
            $table->json('onsetPeriod')->nullable();
            $table->json('onsetRange')->nullable();
            $table->string('onsetString')->nullable();
            $table->dateTime('abatementDateTime')->nullable();
            $table->string('abatementAge')->nullable();
            $table->json('abatementPeriod')->nullable();
            $table->json('abatementRange')->nullable();
            $table->string('abatementString')->nullable();
            $table->dateTime('abatementDateTime')->nullable();
            $table->dateTime('recordedDate')->nullable();
            $table->json('recorder')->nullable();
            $table->json('asserter')->nullable();
            $table->json('stage')->nullable();
            $table->json('evidence')->nullable();
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
        Schema::dropIfExists('conditions');
    }
};
