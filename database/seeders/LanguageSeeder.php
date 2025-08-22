<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            'العربية', // Arabic
            'الإنجليزية', // English
            'الإسبانية', // Spanish
            'الفرنسية', // French
            'الألمانية', // German
            'الصينية', // Chinese
            'الهندية', // Hindi
            'الروسية', // Russian
            'البرتغالية', // Portuguese
            'اليابانية', // Japanese
            'الكورية', // Korean
            'التركية', // Turkish
            'الإيطالية', // Italian
            'الهولندية', // Dutch
            'الأردية', // Urdu
            'البنغالية', // Bengali
            'الفارسية', // Persian
            'الماليزية', // Malay
            'التايلاندية', // Thai
            'السواحلية', // Swahili
            'الفيتنامية', // Vietnamese
            'اليونانية', // Greek
            'العبرية', // Hebrew
            'البولندية', // Polish
            'التشيكية', // Czech
            'الرومانية', // Romanian
            'الهنغارية', // Hungarian
            'الصربية', // Serbian
            'الأوكرانية', // Ukrainian
            'الإندونيسية', // Indonesian
            'النرويجية', // Norwegian
            'السويدية', // Swedish
            'الفنلندية', // Finnish
            'الدانماركية', // Danish
            'الفلبينية', // Filipino
            'البشتو', // Pashto
            'الصومالية', // Somali
            'الأمهرية', // Amharic
            'الأرمينية', // Armenian
            'الأذربيجانية', // Azerbaijani
            'الباسكية', // Basque
            'البيلاروسية', // Belarusian
            'الكتالانية', // Catalan
            'الإستونية', // Estonian
            'الجورجية', // Georgian
            'الهوسا', // Hausa
            'الإغبو', // Igbo
            'الأيرلندية', // Irish
            'الكازاخية', // Kazakh
            'الكردية', // Kurdish
            'اللاتفية', // Latvian
            'الليتوانية', // Lithuanian
            'المقدونية', // Macedonian
            'المالطية', // Maltese
            'المنغولية', // Mongolian
            'النيبالية', // Nepali
            'السلوفاكية', // Slovak
            'السلوفينية', // Slovenian
            'الطاجيكية', // Tajik
            'التاميلية', // Tamil
            'التيلجو', // Telugu
            'التيغرينية', // Tigrinya
            'اليوروبية', // Yoruba
            'الزولو', // Zulu
        ];

        DB::table('languages')->delete(); // Clear previous English entries

        foreach ($languages as $language) {
            DB::table('languages')->insert([
                'name' => $language,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
