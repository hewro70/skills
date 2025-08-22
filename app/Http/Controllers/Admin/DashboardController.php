<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Skill;
use App\Models\Invitation;
use App\Models\Exchange;
use App\Models\Conversation;
use App\Models\Review;
use App\Models\Country;
use App\Models\Classification;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB; // ✅ استخدام DB Facade بشكل أنظف

class DashboardController extends Controller
{
   
public function index(Request $request)
{
    // ✅ إجمالي عدد المستخدمين
    $totalUsers = User::count();

    // ✅ عدد المستخدمين الجدد هذا الأسبوع
    $newUsersToday = User::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();

    // ✅ جميع الدول من جدول countries لعرضها في القائمة
    $usersByCountry = Country::orderBy('name')->get();

    // ✅ توزيع المستخدمين حسب الجنس
    $usersByGender = User::select('gender', DB::raw('count(*) as total'))
                         ->groupBy('gender')
                         ->get();

    // ✅ التصفية
    $selectedCountry = $request->input('country');
    $selectedGender  = $request->input('gender');

    // ✅ عدد المستخدمين في الدولة المحددة
    $countryResult = null;
    if ($selectedCountry) {
        $countryResult = User::where('country_id', $selectedCountry)->count();
    }


    // ✅ عدد المستخدمين حسب الجنس المحدد
    $genderResult = null;
    if ($selectedGender) {
        $genderResult = User::where('gender', $selectedGender)->count();
    }

    // ✅ المهارات الأكثر طلبًا
    $topSkills = Skill::withCount('exchanges')
                      ->orderBy('exchanges_count', 'desc')
                      ->take(5)
                      ->get();

$topClassification = DB::table('skills')
    ->join('exchanges', 'skills.id', '=', 'exchanges.skill_id')
    ->select('skills.classification_id', DB::raw('COUNT(exchanges.id) as total_exchanges'))
    ->groupBy('skills.classification_id')
    ->orderByDesc('total_exchanges')
    ->first();

// Step 2: Get the top 5 skills from that classification
$topFiveSkills = collect(); // Default to empty collection

if ($topClassification) {
    $topFiveSkills = Skill::withCount('exchanges')
        ->where('classification_id', $topClassification->classification_id)
        ->orderByDesc('exchanges_count')
        ->take(5)
        ->get();
}


$classificationIds = Skill::withCount('classification')
                      ->orderBy('classification_id', 'desc')
                      ->take(5)
                      ->get();

    $sentInvitationsCount = \App\Models\Invitation::count(); // All invitations
$acceptedInvitationsCount = \App\Models\Invitation::where('reply', 'قبول')->count();
$startedExchangesCount = DB::table('exchanges')
    ->where('status', 'started')
    ->count();
$endExchangesCount = DB::table('exchanges')
    ->where('status', 'completed')
    ->count();
$activeChats = DB::table('conversation_user')
    ->where('is_active', true)
    ->distinct()
    ->count('conversation_id');






     $totalChats  = Conversation::count();


// ✅ Percentage calculation (avoid division by zero)
$activeChatRate = $totalChats > 0
    ? round(($activeChats / $totalChats) * 100, 2)
    : 0;


    $averageRating = DB::table('reviews')
    ->avg('ratings') ?? 0;


$writtenReviewsCount = DB::table('reviews')
    ->whereNotNull('comment')
    ->where('comment', '!=', '')
    ->count();


    return view('admin.dashboard', compact(
        'totalUsers',
        'newUsersToday',
        'usersByCountry',
        'usersByGender',
        'selectedCountry',
        'selectedGender',
        'countryResult',
        'genderResult',
        'topSkills',
        'totalChats',
        'activeChats',
        'activeChatRate',
        'topFiveSkills',
        'classificationIds',
        'sentInvitationsCount',
        'startedExchangesCount',
        'endExchangesCount',
        'acceptedInvitationsCount',
        'averageRating',
        'writtenReviewsCount',
    ));
}

public function getUserCountByCountry(Request $request)
{
    $countryId = $request->input('country_id');

    $count = User::where('country_id', $countryId)->count();

    return response()->json([
        'count' => $count
    ]);
}

public function getUserCountByGender(Request $request)
{
    $gender = $request->input('gender');

    $count = User::where('gender', $gender)->count();

    return response()->json([
        'count' => $count
    ]);
}

}
