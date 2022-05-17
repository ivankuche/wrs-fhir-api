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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->json('identifier')->nullable();
            $table->boolean('active')->nullable();
            $table->string('type');
            $table->boolean('actual');
            $table->json('code')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->json('managingEntity')->nullable();
            $table->json('characteristic')->nullable();
            $table->json('member')->nullable();
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
        Schema::dropIfExists('groups');
    }
};
