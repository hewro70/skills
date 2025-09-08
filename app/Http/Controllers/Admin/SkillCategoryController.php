<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkillCategoryController extends Controller
{
    public function index()
{
    $classifications = \App\Models\Classification::orderByDesc('id')->paginate(10);
    $skills = \App\Models\Skill::with('classification')->orderByDesc('id')->paginate(10);

    $locale = app()->getLocale();
    $classificationOptions = \App\Models\Classification::orderByRaw(
        "LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')))"
    )->get(['id','name']);

    return view('admin.skills-categories', compact(
        'classifications',
        'skills',
        'classificationOptions'
    ));
}



    public function storeClassification(Request $request)
    {
        $data = $request->validate([
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
        ], [], [
            'name.ar' => 'الاسم (عربي)',
            'name.en' => 'الاسم (إنجليزي)',
        ]);

        $c = new Classification();
        $c->setTranslations('name', [
            'ar' => $data['name']['ar'],
            'en' => $data['name']['en'],
        ]);
        $c->save();

        return back()->with('success', 'تم إضافة التصنيف بنجاح.');
    }

    public function updateClassification(Request $request, Classification $classification)
    {
        $data = $request->validate([
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
        ], [], [
            'name.ar' => 'الاسم (عربي)',
            'name.en' => 'الاسم (إنجليزي)',
        ]);

        $classification->setTranslations('name', [
            'ar' => $data['name']['ar'],
            'en' => $data['name']['en'],
        ]);
        $classification->save();

        return back()->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroyClassification(Classification $classification)
    {
        if ($classification->skills()->exists()) {
            return back()->with('error', 'لا يمكن حذف التصنيف لارتباطه بمهارات.');
        }

        $classification->delete();
        return back()->with('success', 'تم حذف التصنيف.');
    }


    public function storeSkill(Request $request)
    {
        $data = $request->validate([
            'classification_id' => ['required', Rule::exists('classifications', 'id')],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
        ], [], [
            'classification_id' => 'التصنيف',
            'name.ar' => 'الاسم (عربي)',
            'name.en' => 'الاسم (إنجليزي)',
        ]);

        $s = new Skill();
        $s->classification_id = $data['classification_id'];
        $s->setTranslations('name', [
            'ar' => $data['name']['ar'],
            'en' => $data['name']['en'],
        ]);
        $s->save();

        return back()->with('success', 'تم إضافة المهارة بنجاح.');
    }

    public function updateSkill(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'classification_id' => ['required', Rule::exists('classifications', 'id')],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
        ], [], [
            'classification_id' => 'التصنيف',
            'name.ar' => 'الاسم (عربي)',
            'name.en' => 'الاسم (إنجليزي)',
        ]);

        $skill->classification_id = $data['classification_id'];
        $skill->setTranslations('name', [
            'ar' => $data['name']['ar'],
            'en' => $data['name']['en'],
        ]);
        $skill->save();

        return back()->with('success', 'تم تحديث المهارة بنجاح.');
    }

    public function destroySkill(Skill $skill)
    {
    

        $skill->delete();
        return back()->with('success', 'تم حذف المهارة.');
    }
}
