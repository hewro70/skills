<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('user_skills', function (Blueprint $table) {
            // أضف level فقط إذا مش موجود
            if (!Schema::hasColumn('user_skills', 'level')) {
                $table->unsignedTinyInteger('level')->default(3)->after('skill_id');
            }
        });
    }

    public function down(): void {
        Schema::table('user_skills', function (Blueprint $table) {
            if (Schema::hasColumn('user_skills', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};
