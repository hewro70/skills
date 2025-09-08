<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExchangeController;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ThemeController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SkillCategoryController;

use App\Http\Controllers\Admin\ConversationController as AdminConversationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InvitationController as AdminInvitationController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ReviewController;
use App\Models\Conversation;
use App\Events\ChatMessageSent;
use App\Http\Controllers\ReviewController as UserReviewController;
use App\Http\Controllers\PremiumRequestController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar','en']), 404);
    session(['app_locale' => $locale]);
    return back();
})->name('lang.switch');

// لوحة المستخدم العامة (محميّة)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ============================
|  لوحة تحكم المسؤول (admin)
|=============================*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ⚠️ بدون /admin هنا لأننا أصلاً داخل prefix('admin')
    Route::post('/filter/country', [DashboardController::class, 'getUserCountByCountry'])->name('filter.country');
    Route::post('/filter/gender',  [DashboardController::class, 'getUserCountByGender'])->name('filter.gender');

    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::resource('/users', UserController::class);
    Route::resource('/invitations', AdminInvitationController::class);
    Route::resource('/chats', ChatController::class);
    Route::resource('/reviews', ReviewController::class);

    Route::get('/conversations', [AdminConversationController::class, 'index'])->name('conversations.index');
    Route::delete('/conversations/{id}', [AdminConversationController::class, 'destroy'])->name('conversations.destroy');
        Route::get('/skills-categories', [SkillCategoryController::class, 'index'])->name('skills-categories.index');

        // Classifications
        Route::post('/classifications', [SkillCategoryController::class, 'storeClassification'])->name('classifications.store');
        Route::put('/classifications/{classification}', [SkillCategoryController::class, 'updateClassification'])->name('classifications.update');
        Route::delete('/classifications/{classification}', [SkillCategoryController::class, 'destroyClassification'])->name('classifications.destroy');

        // Skills
        Route::post('/skills', [SkillCategoryController::class, 'storeSkill'])->name('skills.store');
        Route::put('/skills/{skill}', [SkillCategoryController::class, 'updateSkill'])->name('skills.update');
        Route::delete('/skills/{skill}', [SkillCategoryController::class, 'destroySkill'])->name('skills.destroy');
});


/* ============================
|  صفحات الموقع العامة
|=============================*/
Route::controller(ThemeController::class)->name('theme.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/skills', 'skills')->name('skills'); // نفس المنطق
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/privacyPolicy', 'privacyPolicy')->name('privacyPolicy');
    Route::get('/termsOfServices', 'termsOfServices')->name('termsOfServices');
    Route::get('/profile/{user}', 'showProfile')->name('profile.show');
});

// نموذج التواصل (POST)
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
// routes/web.php
Route::post('/premium/requests', [PremiumRequestController::class, 'store'])
    ->name('premium.requests.store')
    ->middleware('auth');

/* ============================
|  مسارات المستخدم (بعد تسجيل الدخول)
|=============================*/
Route::middleware('auth')->group(function () {
    Route::get('/premium', [\App\Http\Controllers\PremiumController::class, 'show'])->name('premium.show');

    // ملف المستخدم (العادي) — استخدم ProfileController (وليس Admin)
    Route::get('/myProfile', [ProfileController::class, 'myProfile'])->name('myProfile'); // ← أبقينا نسخة واحدة فقط

    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload-image');
    Route::post('/profile/remove-image', [ProfileController::class, 'removeImage'])->name('profile.remove-image');
    Route::put('/profile/qualifications', [ProfileController::class, 'updateQualifications'])->name('profile.update-qualifications');

    // API صغيرة للـ Skills/Languages
    Route::get('/api/skills', [ProfileController::class, 'getSkills'])->name('api.skills');
    Route::get('/api/languages', [ProfileController::class, 'getLanguages'])->name('api.languages');

    // الدعوات
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations/send', [InvitationController::class, 'send'])->name('invitations.send');
    Route::post('/invitations/{invitation}/reply', [InvitationController::class, 'reply'])->name('invitations.reply');
    Route::get('/invitation/check-eligibility', [InvitationController::class, 'checkEligibility'])->name('invitations.check');

    Route::get('/exchanges', [InvitationController::class, 'exchanges'])->name('exchanges.index');

    
    // OneSignal
    Route::post('/onesignal/update', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::findOrFail(auth()->id());
        $user->update(['onesignal_player_id' => $request->player_id]);
        return response()->json(['message' => 'Player ID updated']);
    })->name('onesignal.update');

    // عدّاد الدعوات
    Route::get('/invitations/count', function () {
        $count = \App\Models\Invitation::where('destination_user_id', auth()->id())
            ->whereNull('reply')->count();
        return response()->json(['count' => $count]);
    });

    // المحادثات
    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/create', [ConversationController::class, 'create'])->name('create');
        Route::post('/', [ConversationController::class, 'store'])->name('store');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/messages', [ConversationController::class, 'storeMessage'])->name('messages.store');
        Route::post('/{conversation}/leave', [ConversationController::class, 'leave'])->name('leave');
        Route::post('/{conversation}/review', [ConversationController::class, 'storeReview'])->name('review.store');
    });
});
// routes/web.php


Route::middleware('auth')->group(function () {
    Route::prefix('conversations/{conversation}/exchanges')
        ->name('conversations.exchanges.')
        ->group(function () {
            Route::post('/', [ExchangeController::class, 'store'])->name('store');
            Route::post('{exchange}/accept', [ExchangeController::class, 'accept'])->name('accept');
            Route::post('{exchange}/accept-teach-only', [ExchangeController::class, 'acceptTeachOnly'])->name('acceptTeachOnly'); // جديد
            Route::post('{exchange}/reject', [ExchangeController::class, 'reject'])->name('reject');
            Route::post('{exchange}/cancel', [ExchangeController::class, 'cancel'])->name('cancel');
        });
});
Route::middleware('auth')->group(function () {
    Route::get('/invitations/unread-count', [InvitationController::class, 'unreadCount'])
        ->name('invitations.unreadCount');
});
// routes/web.php

Route::middleware('auth')->group(function () {
    Route::get('/notifications/counts', [NotificationController::class, 'counts'])
        ->name('notifications.counts');
});

Route::middleware('auth')->group(function () {
    Route::prefix('conversations/{conversation}/reviews')
        ->name('conversations.reviews.')
        ->group(function () {
            Route::post('/',               [UserReviewController::class, 'store'])->name('store');
            Route::patch('{review}',       [UserReviewController::class, 'update'])->name('update');
            Route::patch('{review}/reply', [UserReviewController::class, 'reply'])->name('reply');
            Route::delete('{review}',      [UserReviewController::class, 'destroy'])->name('destroy');
        });
});


Route::middleware('auth')->get('/test-broadcast/{conv}', function (Conversation $conv) {
    // أنشئ رسالة تجريبية داخل هذه المحادثة
    $msg = $conv->messages()->create([
        'user_id' => auth()->id(),
        'body'    => 'Ping test @ ' . now()->toTimeString(),
    ]);

    // بثّها فورًا
    event(new ChatMessageSent($msg)); // أو broadcast(new ChatMessageSent($msg))->toOthers();

    return response()->json(['ok' => true, 'conv_id' => $conv->id, 'msg_id' => $msg->id]);
});

// ⚠️ لا تعرّف /logout لو تستخدم Breeze/Jetstream (هما بيوفّروا POST /logout باسم route: logout)
// إن كنت تحتاج روت مخصّص، غيّر الاسم:
# Route::post('/logout', function () {
#     Auth::logout();
#     return redirect('/login');
# })->name('auth.custom-logout');

// مصادقة Breeze/Jetstream
require __DIR__ . '/auth.php';
