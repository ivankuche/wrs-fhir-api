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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->string('lifecycleStatus');
            $table->json('achievementStatus')->nullable();
            $table->json('category')->nullable();
            $table->boolean('continuous')->nullable();
            $table->json('priority')->nullable();
            $table->json('description');
            $table->json('subject');
            $table->date('startDate')->nullable();
            $table->json('startCodeableConcept')->nullable();
            $table->json('target')->nullable();
            $table->date('statusDate')->nullable();
            $table->string('statusReason')->nullable();
            $table->json('source')->nullable();
            $table->json('addresses')->nullable();
            $table->json('note')->nullable();
            $table->json('outcome')->nullable();
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
        Schema::dropIfExists('goals');
    }
};
