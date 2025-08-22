<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Country;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        return view("theme.index");
    }

    public function skills(Request $request)
    {
        $query = User::with(['skills', 'country', 'languages']);

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('skills', function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('gender') && !empty($request->gender)) {
            $query->whereIn('gender', $request->gender);
        }

        if ($request->has('countries') && !empty($request->countries)) {
            $query->whereIn('country_id', $request->countries);
        }

        if ($request->has('classifications') && !empty($request->classifications)) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->whereIn('classification_id', $request->classifications);
            });
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'top_rated':
                    
                    break;
                case 'relevant':
                default:
                    
                    break;
            }
        }
        /** @var \Illuminate\Pagination\LengthAwarePaginator $users */
        $users = $query->paginate(10);
        $skills = Skill::with(['classification'])->get();
        $classifications = Classification::all();
        $countries = Country::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('theme.partials.users_grid', ['users' => $users])->render(),
                'pagination' => $users->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view("theme.skills", [
            'users' => $users,
            'skills' => $skills,
            'classifications' => $classifications,
            'countries' => $countries,
        ]);
    }

    public function about()
    {
        return view("theme.about");
    }

    public function contact()
    {
        return view("theme.contact");
    }

    public function login()
    {
        return view('theme.partials.login');
    }
    public function register()
    {
        return view('theme.partials.register');
    }
    public function privacyPolicy()
    {
        return view('theme.Important-links.privacy-policy');
    }
    public function termsOfServices()
    {
        return view('theme.Important-links.termsOfServices');
    }

    public function showProfile(User $user)
    {
        $user->load(['skills', 'country', 'languages']);
        return view('theme.profile', compact('user'));
    }
}
