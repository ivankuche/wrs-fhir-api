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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->json('code')->nullable();
            $table->string('status')->nullable();
            $table->json('manufacturer')->nullable();
            $table->json('form')->nullable();
            $table->json('amount')->nullable();
            $table->json('ingredient')->nullable();
            $table->json('batch')->nullable();
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
        Schema::dropIfExists('medications');
    }
};
