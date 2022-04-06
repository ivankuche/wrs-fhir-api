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
        Schema::create('provenances', function (Blueprint $table) {
            $table->id();
            $table->json('target');
            $table->json('occurred')->nullable();
            $table->timestampTz('recorded')->nullable();
            $table->string('policy')->nullable();
            $table->json('location')->nullable();
            $table->json('authorization')->nullable();
            $table->json('activity')->nullable();
            $table->json('basedOn')->nullable();
            $table->json('patient')->nullable();
            $table->json('encounter')->nullable();
            $table->json('agent')->nullable();
            $table->json('entity')->nullable();
            $table->json('signature')->nullable();
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
        Schema::dropIfExists('provenances');
    }
};
