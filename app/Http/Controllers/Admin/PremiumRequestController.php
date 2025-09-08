<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PremiumRequestController extends Controller
{
    public function index(Request $req)
    {
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
            $items = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 20, 1,
                ['path' => $req->url(), 'query' => $req->query()]
            );
            session()->flash('ok', '⚠️ تعذّر تحميل البيانات مؤقتًا، جرّب لاحقًا.');
        }

        return view('admin.premium_requests.index', compact('items'));
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
}
