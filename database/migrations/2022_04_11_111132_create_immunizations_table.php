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
        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('instantiatesCanonical')->nullable();
            $table->string('instantiatesUri')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('status');
            $table->json('statusReason')->nullable();
            $table->json('vaccineCode');
            $table->json('manufacturer')->nullable();
            $table->string('lotNumber')->nullable();
            $table->date('expirationDate')->nullable();
            $table->json('patient');
            $table->json('encounter')->nullable();
            $table->dateTime('occurrenceDateTime');
            $table->string('occurrenceString')->nullable();
            $table->dateTime('recorded')->nullable();
            $table->boolean('primarySource')->nullable();
            $table->json('informationSource')->nullable();
            $table->json('location')->nullable();
            $table->json('site')->nullable();
            $table->json('route')->nullable();
            $table->json('doseQuantity')->nullable();
            $table->json('performer')->nullable();
            $table->json('note')->nullable();
            $table->json('reason')->nullable();
            $table->boolean('isSubpotent')->nullable();
            $table->json('subpotentReason')->nullable();
            $table->json('education')->nullable();
            $table->json('programEligibility')->nullable();
            $table->json('fundingSource')->nullable();
            $table->json('reaction')->nullable();
            $table->json('protocolApplied')->nullable();
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
        Schema::dropIfExists('immunizations');
    }
};
