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

use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
   
public function index(Request $request)
{
    $totalUsers = User::count();

    $newUsersToday = User::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count();

    $usersByCountry = Country::orderBy('name')->get();

    $usersByGender = User::select('gender', DB::raw('count(*) as total'))
                         ->groupBy('gender')
                         ->get();

    $selectedCountry = $request->input('country');
    $selectedGender  = $request->input('gender');

    $countryResult = null;
    if ($selectedCountry) {
        $countryResult = User::where('country_id', $selectedCountry)->count();
    }


    $genderResult = null;
    if ($selectedGender) {
        $genderResult = User::where('gender', $selectedGender)->count();
    }

$topSkills = Skill::select('skills.*')
    ->selectRaw('
        (SELECT COUNT(*) FROM exchanges e1 WHERE e1.sender_skill_id = skills.id)
        +
        (SELECT COUNT(*) FROM exchanges e2 WHERE e2.receiver_skill_id = skills.id)
        AS exchanges_count
    ')
    ->orderByDesc('exchanges_count')
    ->limit(5)
    ->get();

$topClassification = DB::table('skills')
    ->leftJoin('exchanges as e1', 'skills.id', '=', 'e1.sender_skill_id')
    ->leftJoin('exchanges as e2', 'skills.id', '=', 'e2.receiver_skill_id')
    ->select(
        'skills.classification_id',
        DB::raw('COUNT(DISTINCT e1.id) + COUNT(DISTINCT e2.id) as total_exchanges')
    )
    ->groupBy('skills.classification_id')
    ->orderByDesc('total_exchanges')
    ->first();

$topFiveSkills = collect();
if ($topClassification) {
    $topFiveSkills = Skill::where('classification_id', $topClassification->classification_id)
        ->select('skills.*')
        ->selectRaw('
            (SELECT COUNT(*) FROM exchanges e1 WHERE e1.sender_skill_id = skills.id)
            +
            (SELECT COUNT(*) FROM exchanges e2 WHERE e2.receiver_skill_id = skills.id)
            AS exchanges_count
        ')
        ->orderByDesc('exchanges_count')
        ->limit(5)
        ->get();
}
$classificationIds = DB::table('skills')
    ->leftJoin('exchanges as e1', 'skills.id', '=', 'e1.sender_skill_id')
    ->leftJoin('exchanges as e2', 'skills.id', '=', 'e2.receiver_skill_id')
    ->select(
        'skills.classification_id',
        DB::raw('COUNT(DISTINCT e1.id) + COUNT(DISTINCT e2.id) as total_exchanges')
    )
    ->groupBy('skills.classification_id')
    ->orderByDesc('total_exchanges')
    ->limit(5)
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
