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

        // ✅ استبعد partial و page من الكويري عند بناء روابط الباجينيشن
        $baseQuery = $request->except(['partial', 'page']);

        // ✅ استخدم links(...)->toHtml() بدل view('pagination::bootstrap-5', ...)
        $paginationHtml = $users
            ->appends($baseQuery)
            ->links('pagination::bootstrap-5')
            ->toHtml();

        $chipsHtml = view('theme.partials.active_chips', [
            'countries' => $countries,
            'classifications' => $classifications
        ])->render();

        $total = method_exists($users,'total') ? $users->total() : (is_countable($users) ? count($users) : 0);

        // ✅ ابني URL نظيف بدون partial
        $cleanUrl = url()->current();
        if (!empty($baseQuery)) {
            $cleanUrl .= '?'.http_build_query($baseQuery);
        }

        return response()->json([
            'ok'              => true,
            'users_html'      => $usersHtml,
            'pagination_html' => $paginationHtml,
            'chips_html'      => $chipsHtml,
            'total'           => $total,
            'url'             => $cleanUrl, // لا ترجع partial=1
        ]);
    }

    return view('theme.index', compact('users','skills','classifications','countries','popularSkills'));
}

    private function loadData(Request $request): array
{
    $locale = app()->getLocale();

    $query = User::with(['skills','country','languages']);

    // بحث باسم المهارة (على الترجمة الخاصة باللغة الحالية)
    if ($request->filled('search')) {
        $term = (string) $request->string('search');
        $query->whereHas('skills', fn($q) =>
            $q->where("name->$locale", 'like', "%{$term}%")
        );
        // أو: $q->whereNameLike($term, $locale);
    }

    // باقي الفلاتر كما هي...
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

    if ($badges = (array) $request->input('badges', [])) {
        if (Schema::hasColumn('users','badge')) {
            $query->whereIn('badge', $badges);
        }
    }

    switch ($request->input('sort')) {
        case 'newest':
            $query->latest('id'); break;
        case 'top_rated':
            if (Schema::hasColumn('users','rating')) $query->orderByDesc('rating');
            else $query->latest('id');
            break;
        case 'relevant':
        default:
            // اتركها افتراضي
            break;
    }

    $users = $query->paginate(10)->withQueryString();

    // 👇 رجّع المهارات والتصنيفات مرتّبة بالترجمة الحالية
    $skills = Skill::with('classification')
        ->when(Schema::hasColumn('skills','is_active'), fn($q)=> $q->where('is_active',1))
        ->orderByTranslatedName($locale) // <-- مهم
        ->get();

    $classifications = Classification::query()
        ->orderByTranslatedName($locale) // <-- مهم
        ->get();

    $countries = Country::all(); // لو بدك تترجمها، طبّق نفس النمط

    $popularSkills = Skill::with('classification')
        ->withCount('users')
        ->when(Schema::hasColumn('skills','is_active'), fn($q)=> $q->where('is_active',1))
        ->orderByDesc('users_count')
        ->limit(12)
        ->get(['id','name','classification_id','type']);

    return [$users, $skills, $classifications, $countries, $popularSkills];
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
