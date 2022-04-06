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
        Schema::create('care_plans', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('instantiatesCanonical')->nullable();
            $table->json('instantiatesUri')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('replaces')->nullable();
            $table->json('partOf')->nullable();
            $table->string('status');
            $table->string('intent');
            $table->json('category');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->json('subject');
            $table->json('encounter')->nullable();
            $table->json('period')->nullable();
            $table->dateTime('created')->nullable();
            $table->json('author')->nullable();
            $table->json('contributor')->nullable();
            $table->json('careTeam')->nullable();
            $table->json('addresses')->nullable();
            $table->json('supportingInfo')->nullable();
            $table->json('goal')->nullable();
            $table->json('activity')->nullable();
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
        Schema::dropIfExists('care_plans');
    }
};
