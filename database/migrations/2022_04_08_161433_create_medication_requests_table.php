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
        Schema::create('medication_requests', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->string('status');
            $table->json('statusReason')->nullable();
            $table->string('intent');
            $table->json('category')->nullable();
            $table->string('priority')->nullable();
            $table->boolean('doNotPerform')->nullable();
            $table->boolean('reportedBoolean')->nullable();
            $table->json('reportedReference')->nullable();
            // One of the medication must be mandatory
            $table->json('medicationCodeableConcept')->nullable();
            $table->json('medicationReference')->nullable();
            $table->json('subject');
            $table->json('encounter')->nullable();
            $table->json('supportingInformation')->nullable();
            $table->dateTime('authoredOn')->nullable();
            $table->json('requester')->nullable();
            $table->json('performer')->nullable();
            $table->json('performerType')->nullable();
            $table->json('recorder')->nullable();
            $table->json('reasonCode')->nullable();
            $table->json('reasonReference')->nullable();
            $table->string('instantiatesCanonical')->nullable();
            $table->string('instantiatesUri')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('groupIdentifier')->nullable();
            $table->json('courseOfTherapyType')->nullable();
            $table->json('insurance')->nullable();
            $table->json('note')->nullable();
            $table->json('dosageInstruction')->nullable();
            $table->json('dispenseRequest')->nullable();
            $table->json('substitution')->nullable();
            $table->json('priorPrescription')->nullable();
            $table->json('detectedIssue')->nullable();
            $table->json('eventHistory')->nullable();
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
        Schema::dropIfExists('medication_requests');
    }
};
