<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 20)->nullable()->after('password');
            $table->string('last_name', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();

            $table->enum('role', ['user', 'admin'])->nullable();
            $table->text('about_me')->nullable();

            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('skill_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['skill_id']);
            $table->dropForeign(['language_id']);

            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'date_of_birth',
                'gender',
                'country_id',
                'skill_id',
                'language_id',
                'role',
                'about_me',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
            $table->foreign('skill_id')->references('id')->on('skills')->nullOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->nullOnDelete();
        });
    }
};
