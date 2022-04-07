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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('udi')->nullable();
            $table->string('status')->nullable();
            $table->json('type')->nullable();
            $table->string('lotNumber')->nullable();
            $table->string('manufacturer')->nullable();
            $table->dateTime('manufactureDate')->nullable();
            $table->dateTime('expirationDate')->nullable();
            $table->string('model')->nullable();
            $table->string('version')->nullable();
            $table->json('patient')->nullable();
            $table->json('owner')->nullable();
            $table->json('contact')->nullable();
            $table->json('location')->nullable();
            $table->string('url')->nullable();
            $table->json('note')->nullable();
            $table->json('safety')->nullable();

            // Extension attributes
            $table->string('distinctIdentifier')->nullable();
            $table->string('serialNumber')->nullable();
            $table->json('udiCarrier')->nullable();

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
        Schema::dropIfExists('devices');
    }
};
