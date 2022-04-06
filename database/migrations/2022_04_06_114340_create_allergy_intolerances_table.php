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
        Schema::create('allergy_intolerances', function (Blueprint $table) {
            $table->id();

            $table->json('identifier')->nullable();
            $table->json('clinicalStatus')->nullable();
            $table->json('verificationStatus')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string('criticality')->nullable();
            $table->json('code')->nullable();
            $table->json('patient');
            $table->json('encounter')->nullable();
            $table->dateTime('onsetDateTime')->nullable();
            $table->integer('onsetAge')->nullable();
            $table->json('onsetPeriod')->nullable();
            $table->json('onsetRange')->nullable();
            $table->string('onsetString')->nullable();
            $table->dateTime('recordedDate')->nullable();
            $table->json('recorder')->nullable();
            $table->json('asserter')->nullable();
            $table->dateTime('lastOccurrence')->nullable();
            $table->json('note')->nullable();
            $table->json('reaction')->nullable();
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
        Schema::dropIfExists('allergy_intolerances');
    }
};
