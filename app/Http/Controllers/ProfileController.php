<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Classification;
use App\Models\Country;
use App\Models\Skill;
use App\Models\User;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function myProfile()
{
    $locale = app()->getLocale();

  $user = User::with([
    'skills' => function ($q) { $q->withPivot('level', 'description'); },
    'languages' => function ($q) { $q->withPivot('level'); },
    'country'
])->find(auth()->id());


    $countries = Country::all();
    $languages = Language::all();

    // التصنيفات + المهارات داخلها
    $classifications = Classification::with('skills')->get();
    $this->decorateNames($classifications, $locale); // <= سنضيفها تحت

    // المهارات المستقلة للجدول
    $skills = Skill::with('classification')
        ->orderByRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))) ASC")
        ->get();

    $this->decorateNames($skills, $locale); // <= تضيف name_text + classification_name_text

    return view('theme.myProfile.master', compact(
        'user','countries','skills','languages','classifications'
    ));
}



    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    $user->fill($request->only([
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'about_me',
        'country_id',
    ]));

    // 👇 إضافة is_mentor (Checkbox في الفورم)
    $user->is_mentor = $request->boolean('is_mentor'); 

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/profile-images');
        $user->image_path = str_replace('public/', '', $path);
    }

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    // === Skills Sync (زي ما هو) ===
    $skillsPayload = json_decode($request->input('skills_data'), true) ?? [];

    if (array_is_list($skillsPayload)) {
        $tmp = [];
        foreach ($skillsPayload as $rawId) {
            if (is_numeric($rawId) && $rawId > 0) {
                $tmp[(int)$rawId] = ['level' => 3, 'description' => null];
            }
        }
        $skillsPayload = $tmp;
    }

    $syncData = [];
    foreach ($skillsPayload as $skillId => $data) {
        $skillId = (int) $skillId;
        if ($skillId <= 0) continue;

        $level = isset($data['level']) ? (int)$data['level'] : 3;
        $level = max(1, min(5, $level));

        $syncData[$skillId] = [
            'level' => $level,
            'description' => isset($data['description']) && $data['description'] !== ''
                ? (string) $data['description']
                : null,
        ];
    }

    $user->skills()->sync($syncData);

    // === Languages Sync ===
    $languagesData = json_decode($request->input('languages_data'), true) ?? [];
    $languagesSyncData = [];

    foreach ($languagesData as $langId => $data) {
        $langId = (int)$langId;
        if ($langId <= 0) continue;

        if (isset($data['level']) && !empty($data['level'])) {
            $languagesSyncData[$langId] = ['level' => $data['level']];
        }
    }

    $user->languages()->sync($languagesSyncData);

    return Redirect::route('myProfile')->with('success', 'تم تحديث المعلومات بنجاح!');
}

private function decorateNames($items, string $locale)
{
    foreach ($items as $item) {
        // نص صريح للواجهة
        if (method_exists($item, 'getTranslation')) {
            $item->setAttribute('name_text', $item->getTranslation('name', $locale) ?? '');
        } else {
            $item->setAttribute('name_text', (string)($item->name ?? ''));
        }

        // لو فيه تصنيف مرتبط
        if ($item->relationLoaded('classification') && $item->classification) {
            if (method_exists($item->classification, 'getTranslation')) {
                $item->classification->setAttribute(
                    'name_text',
                    $item->classification->getTranslation('name', $locale) ?? ''
                );
            } else {
                $item->classification->setAttribute(
                    'name_text',
                    (string)($item->classification->name ?? '')
                );
            }
            // كمان حطّه على نفس المهارة لتسهيل الطباعة
            $item->setAttribute('classification_name_text', $item->classification->getAttribute('name_text'));
        } else {
            $item->setAttribute('classification_name_text', '');
        }
    }
    return $items;
}

    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            $user = $request->user();
            $path = $request->file('profile_image')->store('profile_images', 'public');

            $user->image_path = $path;
            $user->save();

            return response()->json([
                'message' => 'تم تحديث الصورة بنجاح',
                'image_url' => $user->image_url,
                'debug_path' => $path
            ]);
        }

        return response()->json(['message' => 'لم يتم اختيار صورة'], 400);
    }

    public function removeImage(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->image_path && Storage::disk('public')->exists($user->image_path)) {
            Storage::disk('public')->delete($user->image_path);
        }

        $user->image_path = null;
        $user->save();

        return response()->json(['success' => true]);
    }

  public function getSkills(Request $request)
{
    $perPage = 10;
    $page    = (int) $request->get('page', 1);
    $search  = trim((string) $request->get('search', ''));
    $classificationId = $request->integer('classification_id') ?: null;
    $locale  = app()->getLocale();

    $q = Skill::with('classification');

    if ($search !== '') {
        $q->where("name->$locale", 'like', "%{$search}%");
    }

    if ($classificationId) {
        $q->where('classification_id', $classificationId);
    }

    $q->orderByRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))) ASC");

    $skills = $q->paginate($perPage, ['*'], 'page', $page);

    $data = collect($skills->items())->map(function (Skill $s) use ($locale) {
        return [
            'id'                        => $s->id,
            'name_text'                 => $s->getTranslation('name', $locale),
            'classification_id'         => $s->classification_id,
            'classification_name_text'  => $s->classification
                ? $s->classification->getTranslation('name', $locale)
                : '',
        ];
    });

    return response()->json([
        'data'         => $data,
        'total'        => $skills->total(),
        'current_page' => $skills->currentPage(),
        'last_page'    => $skills->lastPage(),
    ]);
}



    public function getLanguages(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);
        $search = $request->get('search', '');

        $query = Language::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $languages = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $languages->items(),
            'total' => $languages->total(),
            'current_page' => $languages->currentPage(),
            'last_page' => $languages->lastPage(),
        ]);
    }
private function hydrateNames($items, string $locale)
{
    foreach ($items as $item) {
        if (method_exists($item, 'getTranslation')) {
            $item->setAttribute('name', $item->getTranslation('name', $locale));
        }

        if ($item->relationLoaded('classification') && $item->classification && method_exists($item->classification, 'getTranslation')) {
            $item->classification->setAttribute('name', $item->classification->getTranslation('name', $locale));
        }
    }
    return $items;
}

    public function updateQualifications(Request $request)
    {
        $user = $request->user();
        $skillsData = $request->input('skills', []);

        foreach ($skillsData as $skillId => $data) {
            $user->skills()->updateExistingPivot($skillId, [
                'description' => $data['description'] ?? null
            ]);
        }

        return response()->json([
            'message' => 'تم تحديث المؤهلات بنجاح'
        ]);
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
