<?php
// database/migrations/2025_08_23_000001_alter_exchanges_add_sender_receiver_fields.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) فك قيود المفاتيح الأجنبية القديمة أولاً
        Schema::table('exchanges', function (Blueprint $t) {
            // إذا كان Laravel مسمي القيد تلقائيًا: exchanges_user_id_foreign / exchanges_skill_id_foreign
            try { $t->dropForeign(['user_id']); } catch (\Throwable $e) {}
            try { $t->dropForeign(['skill_id']); } catch (\Throwable $e) {}
        });

        // 2) احذف الأعمدة القديمة بعد فك القيود
        Schema::table('exchanges', function (Blueprint $t) {
            if (Schema::hasColumn('exchanges','user_id'))  { $t->dropColumn('user_id'); }
            if (Schema::hasColumn('exchanges','skill_id')) { $t->dropColumn('skill_id'); }
        });

        // 3) أضف الأعمدة الجديدة + القيود + الفهارس
        Schema::table('exchanges', function (Blueprint $t) {
            if (!Schema::hasColumn('exchanges','conversation_id')) {
                $t->foreignId('conversation_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }

            if (!Schema::hasColumn('exchanges','sender_id')) {
                $t->foreignId('sender_id')->after('conversation_id')->constrained('users')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('exchanges','receiver_id')) {
                $t->foreignId('receiver_id')->after('sender_id')->constrained('users')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('exchanges','sender_skill_id')) {
                $t->foreignId('sender_skill_id')->after('receiver_id')->constrained('skills')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('exchanges','receiver_skill_id')) {
                $t->foreignId('receiver_skill_id')->after('sender_skill_id')->constrained('skills')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('exchanges','status')) {
                $t->enum('status', ['pending','accepted','rejected','cancelled'])
                  ->default('pending')->after('receiver_skill_id');
            }

            if (!Schema::hasColumn('exchanges','message_for_receiver')) {
                $t->text('message_for_receiver')->nullable()->after('status');
            }

            // سمّي الفهرس لسهولة الحذف لاحقًا
            $t->index(['conversation_id','status'], 'exchanges_conversation_status_idx');
        });
    }

    public function down(): void
    {
        // فك قيود الأعمدة الجديدة قبل الحذف
        Schema::table('exchanges', function (Blueprint $t) {
            try { $t->dropForeign(['conversation_id']); } catch (\Throwable $e) {}
            try { $t->dropForeign(['sender_id']); } catch (\Throwable $e) {}
            try { $t->dropForeign(['receiver_id']); } catch (\Throwable $e) {}
            try { $t->dropForeign(['sender_skill_id']); } catch (\Throwable $e) {}
            try { $t->dropForeign(['receiver_skill_id']); } catch (\Throwable $e) {}

            // احذف الفهرس المسمّى
            try { $t->dropIndex('exchanges_conversation_status_idx'); } catch (\Throwable $e) {}

            // احذف الأعمدة
            foreach ([
                'message_for_receiver','status',
                'receiver_skill_id','sender_skill_id',
                'receiver_id','sender_id','conversation_id'
            ] as $col) {
                if (Schema::hasColumn('exchanges', $col)) {
                    $t->dropColumn($col);
                }
            }

            // (اختياري) ترجيع الأعمدة القديمة
            // $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            // $t->foreignId('skill_id')->constrained()->cascadeOnDelete();
        });
    }
};
