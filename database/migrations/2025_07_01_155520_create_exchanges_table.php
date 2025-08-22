<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('exchanges', function (Blueprint $table) {
        $table->id();
        $table->foreignId('skill_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('status')->default('pending'); // pending / started / completed
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('exchanges');
}

};
