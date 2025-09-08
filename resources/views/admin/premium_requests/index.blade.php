@extends('layout.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">طلبات البريميوم</h2>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="s" value="{{ request('s') }}" class="form-control"
                   placeholder="بحث (اسم/بريد/TxID/مزود/مرجع)">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">كل الحالات</option>
                <option value="pending"  @selected(request('status')==='pending')>معلقة</option>
                <option value="approved" @selected(request('status')==='approved')>مقبولة</option>
                <option value="rejected" @selected(request('status')==='rejected')>مرفوضة</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark w-100">تصفية</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>بريد الطلب</th>
                    <th>مزود</th>
                    <th>TxID</th>
                    <th>المرجع</th>
                    <th>ملاحظات</th>
                    <th>الحالة</th>
                    <th>تاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i => $req)
                    <tr>
                        <td>{{ $items->firstItem() + $i }}</td>
                        <td>{{ trim($req->user_name) !== '' ? $req->user_name : ($req->user_email ?? '-') }}</td>
                        <td>{{ $req->email }}</td>
                        <td>{{ $req->provider }}</td>
                        <td>{{ $req->txid }}</td>
                        <td>{{ $req->reference }}</td>
                        <td>{{ $req->note ?? '-' }}</td>
                        <td>
                            @if($req->status === 'approved')
                                <span class="badge bg-success">مقبول</span>
                            @elseif($req->status === 'rejected')
                                <span class="badge bg-danger">مرفوض</span>
                            @else
                                <span class="badge bg-warning text-dark">معلق</span>
                            @endif
                        </td>
                        <td>{{ $req->created_at }}</td>
                        <td class="d-flex gap-1 flex-wrap">
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('admin.premium-requests.approve',$req->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success">اعتماد</button>
                                </form>
                                <form method="POST" action="{{ route('admin.premium-requests.reject',$req->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">رفض</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.premium-requests.destroy',$req->id) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">لا توجد طلبات حالياً</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $items->links() }}
</div>
@endsection
