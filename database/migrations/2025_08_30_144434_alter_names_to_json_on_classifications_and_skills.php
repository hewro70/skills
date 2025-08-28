<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== classifications =====
        // لو في unique قديم على name لازم يتشال قبل التحويل لـ JSON
        try {
            DB::statement('ALTER TABLE `classifications` DROP INDEX `classifications_name_unique`');
        } catch (\Throwable $e) {
            // تجاهل لو ما في اندكس
        }

        // لفّ البيانات الحالية إلى JSON (لو كانت string)
        DB::statement("
            UPDATE `classifications`
            SET `name` = CASE
                WHEN JSON_VALID(`name`) THEN `name`
                ELSE JSON_OBJECT('en', `name`, 'ar', `name`)
            END
        ");

        // حوّل النوع إلى JSON وأضف الأعمدة المولّدة للفهرسة
        Schema::table('classifications', function (Blueprint $table) {
            $table->json('name')->change();

            // أعمدة مولّدة (Stored Generated) تُستخرج تلقائياً من JSON
            if (!Schema::hasColumn('classifications', 'name_en')) {
                $table->string('name_en')
                      ->storedAs("json_unquote(json_extract(`name`, '$.en'))")
                      ->nullable();
                $table->index('name_en');
            }
            if (!Schema::hasColumn('classifications', 'name_ar')) {
                $table->string('name_ar')
                      ->storedAs("json_unquote(json_extract(`name`, '$.ar'))")
                      ->nullable();
                $table->index('name_ar');
            }
        });

        // ===== skills =====
        DB::statement("
            UPDATE `skills`
            SET `name` = CASE
                WHEN JSON_VALID(`name`) THEN `name`
                ELSE JSON_OBJECT('en', `name`, 'ar', `name`)
            END
        ");

        Schema::table('skills', function (Blueprint $table) {
            $table->json('name')->change();

            if (!Schema::hasColumn('skills', 'name_en')) {
                $table->string('name_en')
                      ->storedAs("json_unquote(json_extract(`name`, '$.en'))")
                      ->nullable();
                $table->index('name_en');
            }
            if (!Schema::hasColumn('skills', 'name_ar')) {
                $table->string('name_ar')
                      ->storedAs("json_unquote(json_extract(`name`, '$.ar'))")
                      ->nullable();
                $table->index('name_ar');
            }
        });
    }

    public function down(): void
    {
        // رجّع الأعمدة إلى string، وخذ قيمة en (أو ar كبديل)
        // skills
        DB::statement("
            UPDATE `skills`
            SET `name` = COALESCE(
                JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')),
                JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ar')),
                ''
            )
            WHERE JSON_VALID(`name`)
        ");

        Schema::table('skills', function (Blueprint $table) {
            // احذف الأعمدة المولّدة والفهارس
            if (Schema::hasColumn('skills', 'name_en')) {
                $table->dropIndex(['name_en']);
                $table->dropColumn('name_en');
            }
            if (Schema::hasColumn('skills', 'name_ar')) {
                $table->dropIndex(['name_ar']);
                $table->dropColumn('name_ar');
            }
            $table->string('name')->change(); // رجوع إلى string
        });

        // classifications
        DB::statement("
            UPDATE `classifications`
            SET `name` = COALESCE(
                JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')),
                JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ar')),
                ''
            )
            WHERE JSON_VALID(`name`)
        ");

        Schema::table('classifications', function (Blueprint $table) {
            if (Schema::hasColumn('classifications', 'name_en')) {
                $table->dropIndex(['name_en']);
                $table->dropColumn('name_en');
            }
            if (Schema::hasColumn('classifications', 'name_ar')) {
                $table->dropIndex(['name_ar']);
                $table->dropColumn('name_ar');
            }
            $table->string('name')->change();

            // (اختياري) إعادة unique على name كما كان:
            $table->unique('name', 'classifications_name_unique');
        });
    }
};
