<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;   // <-- أضف هذا
use Illuminate\Support\Facades\Log;      // <-- (اختياري) للتسجيل

class PremiumRequestController extends Controller
{
    public function index(Request $req)
    {
        $view = $req->input('view', 'requests');

        if ($view === 'users') {
            try {
                $s    = trim($req->input('s', ''));
                $only = $req->input('only', 'all'); // all|premium|free

                // تأكّد من الأعمدة الموجودة فعلياً
                $hasFirst = Schema::hasColumn('users','first_name');
                $hasLast  = Schema::hasColumn('users','last_name');
                $hasName  = Schema::hasColumn('users','name');

                // نبني full_name بأمان حسب الموجود
                if ($hasFirst || $hasLast) {
                    $fullNameExpr = DB::raw(
                        "NULLIF(TRIM(CONCAT(".
                        ($hasFirst ? "COALESCE(first_name,'')" : "''").
                        ",' ', ".
                        ($hasLast ? "COALESCE(last_name,'')" : "''").
                        ")), '') as full_name"
                    );
                } elseif ($hasName) {
                    $fullNameExpr = DB::raw("NULLIF(NULLIF(name,''), NULL) as full_name");
                } else {
                    $fullNameExpr = DB::raw("NULL as full_name");
                }

                $select = [
                    'id',
                    'email',
                    'is_premium',
                    'created_at',
                    'updated_at',
                    $fullNameExpr,
                ];
                if ($hasName) {
                    $select[] = 'name';
                }

                $uq = DB::table('users')->select($select)->orderByDesc('id');

                // البحث: استخدم فقط الأعمدة المتوفرة
                if ($s !== '') {
                    $like = "%{$s}%";
                    $uq->where(function($qq) use ($like, $hasFirst, $hasLast, $hasName) {
                        $qq->where('id', 'like', $like)
                           ->orWhere('email', 'like', $like);

                        if ($hasFirst) $qq->orWhere('first_name', 'like', $like);
                        if ($hasLast)  $qq->orWhere('last_name',  'like', $like);
                        if ($hasName)  $qq->orWhere('name',       'like', $like);
                    });
                }

                if ($only === 'premium') {
                    $uq->where('is_premium', true);
                } elseif ($only === 'free') {
                    $uq->where(function($qq){
                        $qq->whereNull('is_premium')->orWhere('is_premium', false);
                    });
                }

                $users = $uq->paginate(25)->withQueryString();
            } catch (\Throwable $e) {
                // سجّل الخطأ للمراجعة
                Log::error('Users load failed', ['msg' => $e->getMessage()]);
                // لا توقع الصفحة
                $users = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(), 0, 25, 1,
                    ['path' => $req->url(), 'query' => $req->query()]
                );
                session()->flash('ok', '⚠️ تعذّر تحميل المستخدمين مؤقتًا، جرّب لاحقًا.');
            }

            return view('admin.premium_requests.index', [
                'view'  => 'users',
                'users' => $users,
                // نمرر items = null عشان القالب موحّد
                'items' => null,
            ]);
        }

        // ------ تبويب الطلبات كما هو (بدون تغيير) ------
        try {
            $q = DB::table('premium_requests as pr')
                ->leftJoin('users as u', 'u.id', '=', 'pr.user_id')
                ->select([
                    'pr.id',
                    'pr.user_id',
                    'pr.provider',
                    'pr.txid',
                    'pr.email',
                    'pr.note',
                    'pr.reference',
                    'pr.status',
                    'pr.created_at',
                    DB::raw("COALESCE(CONCAT(COALESCE(u.first_name,''),' ',COALESCE(u.last_name,'')), '') as user_name"),
                    'u.email as user_email',
                ])
                ->orderByDesc('pr.id');

            if ($req->filled('status')) {
                $q->where('pr.status', $req->input('status'));
            }

            if ($s = trim($req->input('s', ''))) {
                $q->where(function ($qq) use ($s) {
                    $like = "%{$s}%";
                    $qq->where('pr.email', 'like', $like)
                       ->orWhere('pr.txid', 'like', $like)
                       ->orWhere('pr.provider', 'like', $like)
                       ->orWhere('pr.reference', 'like', $like)
                       ->orWhere('u.first_name', 'like', $like)
                       ->orWhere('u.last_name', 'like', $like)
                       ->orWhere('u.email', 'like', $like);
                });
            }

            $items = $q->paginate(20)->withQueryString();
        } catch (\Throwable $e) {
            Log::error('Premium requests load failed', ['msg' => $e->getMessage()]);
            $items = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 20, 1,
                ['path' => $req->url(), 'query' => $req->query()]
            );
            session()->flash('ok', '⚠️ تعذّر تحميل البيانات مؤقتًا، جرّب لاحقًا.');
        }

        return view('admin.premium_requests.index', [
            'view'  => 'requests',
            'items' => $items,
            'users' => null,
        ]);
    }
public function approve($id)
    {
        DB::table('premium_requests')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now(),
        ]);

        $row = DB::table('premium_requests')->where('id', $id)->first(['user_id']);
        if ($row && $row->user_id) {
            DB::table('users')->where('id', $row->user_id)->update([
                'is_premium' => true,
                'updated_at' => now(),
            ]);
        }

        return back()->with('ok', '✅ تم اعتماد الطلب وتحويل المستخدم إلى بريميوم.');
    }

    public function reject($id)
    {
        DB::table('premium_requests')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);

        return back()->with('ok', '❌ تم رفض الطلب.');
    }

    public function destroy($id)
    {
        DB::table('premium_requests')->where('id', $id)->delete();
        return back()->with('ok', '🗑️ تم حذف الطلب.');
    }

    // ====== تفعيل/إلغاء البريميوم من جدول المستخدمين (بنفس الكنترولر) ======
    public function setPremium($userId)
    {
        DB::table('users')->where('id', $userId)->update([
            'is_premium' => true,
            'updated_at' => now(),
        ]);
        return back()->with('ok', '✅ تم تفعيل البريميوم للمستخدم.');
    }

    public function unsetPremium($userId)
    {
        DB::table('users')->where('id', $userId)->update([
            'is_premium' => false,
            'updated_at' => now(),
        ]);
        return back()->with('ok', '🟡 تم إلغاء البريميوم عن المستخدم.');
    }    // set/unset premium كما أرسلته لك سابقاً...
}
