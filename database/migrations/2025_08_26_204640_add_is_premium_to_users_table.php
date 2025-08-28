<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_is_premium_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('email');
            // (اختياري) تاريخ انتهاء:
            // $table->timestamp('premium_until')->nullable()->after('is_premium');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_premium'/*,'premium_until'*/]);
        });
    }
};
