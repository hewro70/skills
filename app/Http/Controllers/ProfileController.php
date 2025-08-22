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
        $user = User::with(['skills', 'languages' => function ($query) {
            $query->withPivot('level');
        }, 'country'])
            ->find(auth()->id());

        $countries = Country::all();
        $skills = Skill::with('classification')->get();
        $languages = Language::all();
        $classifications = Classification::with('skills')->get();
            
        return view('theme.myProfile.master', compact(
            'user',
            'countries',
            'skills',
            'languages',
            'classifications'
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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/profile-images');
            $user->image_path = str_replace('public/', '', $path);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $skillsData = json_decode($request->input('skills_data'), true) ?? [];
        $validSkills = array_filter($skillsData, function ($id) {
            return is_numeric($id) && $id > 0;
        });
        $user->skills()->sync($validSkills);

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
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $classificationId = $request->get('classification_id', null);

        $query = Skill::with('classification');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($classificationId) {
            $query->where('classification_id', $classificationId);
        }

        $skills = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $skills->items(),
            'total' => $skills->total(),
            'current_page' => $skills->currentPage(),
            'last_page' => $skills->lastPage(),
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
