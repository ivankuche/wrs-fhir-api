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
/*

            "" : "<string>",
            "recordedDate" : "<dateTime>", // Date record was first recorded
            "recorder" : { Reference(Practitioner|PractitionerRole|Patient|
             RelatedPerson) }, // Who recorded the condition
            "asserter" : { Reference(Practitioner|PractitionerRole|Patient|
             RelatedPerson) }, // Person who asserts this condition
            "stage" : [{ // Stage/grade, usually assessed formally
              "summary" : { CodeableConcept }, // C? Simple summary (disease specific)
              "assessment" : [{ Reference(ClinicalImpression|DiagnosticReport|Observation) }], // C? Formal record of assessment
              "type" : { CodeableConcept } // Kind of staging
            }],
            "evidence" : [{ // Supporting evidence
              "code" : [{ CodeableConcept }], // C? Manifestation/symptom
              "detail" : [{ Reference(Any) }] // C? Supporting information found elsewhere
            }],
            "note" : [{ Annotation }] // Additional information about the Condition

            $table->timestamps();
        });
        */
        }
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
