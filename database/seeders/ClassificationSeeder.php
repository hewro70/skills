<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            'اللغات',
            'الرياضة',
            'العلوم',
            'الأعمال',
            'الاقتصاد',
            'البرمجة',
            'التصميم',
            'الرياضيات',
            'الفيزياء',
            'الكيمياء',
            'الأحياء',
            'الطب',
            'الهندسة',
            'الفنون',
            'التعليم',
            'التسويق',
            'الإدارة',
            'الكتابة',
            'الترجمة',
            'التحليل',
        ];

        foreach ($classifications as $name) {
            Classification::firstOrCreate(['name' => $name]);
        }
    }
}
