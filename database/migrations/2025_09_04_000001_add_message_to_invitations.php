<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إضافة عمود message (نصي قابل للإطالة) لاستخدامه في الدعوات للبريميوم
        Schema::table('invitations', function (Blueprint $table) {
            if (!Schema::hasColumn('invitations', 'message')) {
                $table->text('message')->nullable()->after('date_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            if (Schema::hasColumn('invitations', 'message')) {
                $table->dropColumn('message');
            }
        });
    }
};
