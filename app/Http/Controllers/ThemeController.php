<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Country;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ThemeController extends Controller
{
   public function index(Request $request)
{
    [$users, $skills, $classifications, $countries, $popularSkills] = $this->loadData($request);

    if ($request->ajax() || $request->boolean('partial')) {
        $usersHtml = view('theme.partials.users_grid', ['users' => $users])->render();

        $baseQuery = $request->except(['partial','page']);

        $paginationHtml = $users
            ->appends($baseQuery)
            ->links('pagination::bootstrap-5')
            ->toHtml();

        $chipsHtml = view('theme.partials.active_chips', [
            'countries' => $countries,
            'classifications' => $classifications
        ])->render();

        $total = method_exists($users,'total') ? $users->total() : (is_countable($users) ? count($users) : 0);

        $cleanUrl = url()->current();
        if (!empty($baseQuery)) $cleanUrl .= '?'.http_build_query($baseQuery);
        $currentPage = method_exists($users,'currentPage') ? $users->currentPage() : (int)$request->query('page',1);
        $finalUrl = $cleanUrl.(str_contains($cleanUrl,'?') ? '&' : '?').'page='.$currentPage;

        return response()->json([
            'ok'              => true,
            'users_html'      => $usersHtml,
            'pagination_html' => $paginationHtml,
            'chips_html'      => $chipsHtml,
            'total'           => $total,
            'url'             => $finalUrl,
        ]);
    }

    return view('theme.index', compact('users','skills','classifications','countries','popularSkills'));
}
public function skills(Request $request)
{
    [$users, $skills, $classifications, $countries, $popularSkills] = $this->loadData($request);

    if ($request->ajax() || $request->boolean('partial')) {
        $usersHtml = view('theme.partials.users_grid', ['users' => $users])->render();

        $baseQuery = $request->except(['partial','page']);

        $paginationHtml = $users
            ->appends($baseQuery)
            ->links('pagination::bootstrap-5')
            ->toHtml();

        $chipsHtml = view('theme.partials.active_chips', [
            'countries' => $countries,
            'classifications' => $classifications
        ])->render();

        $total = method_exists($users,'total') ? $users->total() : (is_countable($users) ? count($users) : 0);

        $cleanUrl = url()->current();
        if (!empty($baseQuery)) $cleanUrl .= '?'.http_build_query($baseQuery);
        $currentPage = method_exists($users,'currentPage') ? $users->currentPage() : (int)$request->query('page',1);
        $finalUrl = $cleanUrl.(str_contains($cleanUrl,'?') ? '&' : '?').'page='.$currentPage;

        return response()->json([
            'ok'              => true,
            'users_html'      => $usersHtml,
            'pagination_html' => $paginationHtml,
            'chips_html'      => $chipsHtml,
            'total'           => $total,
            'url'             => $finalUrl,
        ]);
    }

    return view('theme.skills', compact('users','skills','classifications','countries','popularSkills'));
}

private function loadData(Request $request): array
{
    $locale = app()->getLocale();

    $query = User::query()->select('users.*')->with(['skills','country','languages']);

    if ($request->filled('search')) {
        $term = trim((string) $request->input('search'));
        $driver = \DB::getDriverName();

        $query->whereHas('skills', function ($q) use ($term, $locale, $driver) {
            if ($driver === 'mysql') {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.\"$locale\"')) LIKE ?", ["%{$term}%"]);
            } elseif ($driver === 'pgsql') {
                $q->whereRaw("(name->>?) ILIKE ?", [$locale, "%{$term}%"]);
            } else {
                $q->where("name->$locale", 'like', "%{$term}%");
            }
        });
    }

    if ($type = $request->input('type')) {
        $query->whereHas('skills', fn($q)=> $q->where('type', $type));
    }
    if ($genders = (array) $request->input('gender', [])) {
        $query->whereIn('gender', $genders);
    }
    if ($countriesFilter = (array) $request->input('countries', [])) {
        $query->whereIn('country_id', $countriesFilter);
    }
    if ($classes = (array) $request->input('classifications', [])) {
        $query->whereHas('skills', fn($q)=> $q->whereIn('classification_id', $classes));
    }
    if ($badges = (array) $request->input('badge', [])) {
        if (Schema::hasColumn('users','badge')) {
            $query->whereIn('badge', $badges);
        }
    }
    // مستقل
    if ($request->boolean('mentor_only')) {
        $query->where('is_mentor', true);
    }

    switch ($request->input('sort')) {
        case 'newest':    $query->latest('id'); break;
        case 'top_rated': Schema::hasColumn('users','rating') ? $query->orderByDesc('rating') : $query->latest('id'); break;
        case 'relevant':
        default: break;
    }

    $query->distinct();
    $users = $query->paginate(10)->withQueryString();

    $skills = Skill::with('classification')
        ->when(Schema::hasColumn('skills','is_active'), fn($q)=> $q->where('is_active',1))
        ->orderByTranslatedName($locale)
        ->get();

    $classifications = Classification::query()->orderByTranslatedName($locale)->get();
    $countries = Country::all();

    $popularSkills = Skill::with('classification')
        ->withCount('users')
        ->when(Schema::hasColumn('skills','is_active'), fn($q)=> $q->where('is_active',1))
        ->orderByDesc('users_count')
        ->limit(12)
        ->get(['id','name','classification_id','type']);

    return [$users,$skills,$classifications,$countries,$popularSkills];
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
        return view('theme.important-links.privacy-policy');
    }
    public function termsOfServices()
    {
        return view('theme.important-links.termsOfServices');
    }

    public function showProfile(User $user)
    {
        $user->load(['skills', 'country', 'languages']);
        return view('theme.profile', compact('user'));
    }
}
