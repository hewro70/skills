<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('exchanges', 'sender_skill_id')) {
            return; // ما في عمود، نطلع بهدوء
        }

        // 1) ابحث عن اسم قيد الـFK الحقيقي (إن وُجد)
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'exchanges'
              AND COLUMN_NAME = 'sender_skill_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        // 2) احذف القيد إن وُجد
        if ($fk && isset($fk->CONSTRAINT_NAME)) {
            DB::statement("ALTER TABLE `exchanges` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 3) عدّل العمود ليصبح NULL (بدون DBAL)
        DB::statement("ALTER TABLE `exchanges` MODIFY `sender_skill_id` BIGINT UNSIGNED NULL");

        // 4) تأكد من وجود فهرس على العمود (اختياري لكنه مفيد)
        $hasIndex = DB::selectOne("
            SELECT 1 FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'exchanges'
              AND COLUMN_NAME = 'sender_skill_id'
            LIMIT 1
        ");
        if (!$hasIndex) {
            DB::statement("ALTER TABLE `exchanges` ADD INDEX `exchanges_sender_skill_id_index` (`sender_skill_id`)");
        }

        // 5) أعد إضافة قيد FK باسم معروف مع ON DELETE SET NULL
        DB::statement("
            ALTER TABLE `exchanges`
            ADD CONSTRAINT `exchanges_sender_skill_id_foreign`
            FOREIGN KEY (`sender_skill_id`) REFERENCES `skills`(`id`)
            ON UPDATE CASCADE ON DELETE SET NULL
        ");
    }

    public function down(): void
    {
        if (!Schema::hasColumn('exchanges', 'sender_skill_id')) {
            return;
        }

        // اسقاط القيد لو موجود (سواء باسمنا أو أي اسم)
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'exchanges'
              AND COLUMN_NAME = 'sender_skill_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");
        if ($fk && isset($fk->CONSTRAINT_NAME)) {
            DB::statement("ALTER TABLE `exchanges` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // إعادة العمود NOT NULL (لو بدك ترجّعه كما كان)
        DB::statement("ALTER TABLE `exchanges` MODIFY `sender_skill_id` BIGINT UNSIGNED NOT NULL");

        // إعادة القيد بأسلوب عادي (حذف السجل المرتبط)
        DB::statement("
            ALTER TABLE `exchanges`
            ADD CONSTRAINT `exchanges_sender_skill_id_foreign`
            FOREIGN KEY (`sender_skill_id`) REFERENCES `skills`(`id`)
            ON UPDATE CASCADE ON DELETE CASCADE
        ");
    }
};
