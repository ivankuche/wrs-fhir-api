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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->string('active')->nullable();
            $table->json('type')->nullable();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->json('partOf')->nullable();
            $table->json('contact')->nullable();
            $table->json('endpoint')->nullable();
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
        Schema::dropIfExists('organizations');
    }
};
