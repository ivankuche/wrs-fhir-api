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
        Schema::create('document_references', function (Blueprint $table) {
            $table->id();
            $table->json('identifier');
            $table->json('basedOn')->nullable();
            $table->string('status');
            $table->string('docStatus');
            $table->json('type');
            $table->json('category');
            $table->json('subject');
            $table->json('encounter')->nullable();
            $table->json('event')->nullable();
            $table->json('facilityType')->nullable();
            $table->json('practiceSetting')->nullable();
            $table->json('period')->nullable();
            $table->dateTime('date');
            $table->json('author');
            $table->json('attester')->nullable();
            $table->json('custodian');
            $table->json('relatesTo')->nullable();
            $table->text('description')->nullable();
            $table->json('securityLabel')->nullable();
            $table->json('content')->nullable();
            $table->json('context')->nullable();
            $table->json('sourcePatientInfo')->nullable();
            $table->json('related')->nullable();
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
        Schema::dropIfExists('document_references');
    }
};
