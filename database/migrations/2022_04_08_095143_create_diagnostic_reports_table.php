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
        Schema::create('diagnostic_reports', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('basedOn')->nullable();
            $table->string('status');
            $table->json('category')->nullable();
            $table->json('category:LaboratorySlide')->nullable();
            $table->json('code');
            $table->json('subject')->nullable();
            $table->json('encounter')->nullable();
            $table->dateTime('effectiveDateTime')->nullable();
            $table->json('effectivePeriod')->nullable();
            $table->dateTime('issued')->nullable();
            $table->json('performer')->nullable();
            $table->json('resultsInterpreter')->nullable();
            $table->json('specimen')->nullable();
            $table->json('result')->nullable();
            $table->json('note')->nullable();
            $table->json('imagingStudy')->nullable();
            $table->json('media')->nullable();
            $table->json('composition')->nullable();
            $table->string('conclusion')->nullable();
            $table->json('conclusionCode')->nullable();
            $table->json('presentedForm')->nullable();
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
        Schema::dropIfExists('diagnostic_reports');
    }
};
