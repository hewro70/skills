@extends('layout.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">البريميوم</h2>
        <div class="btn-group">
            <a href="{{ route('admin.premium-requests.index', array_merge(request()->except('page'), ['view' => 'requests'])) }}"
               class="btn btn-sm {{ ($view ?? 'requests')==='requests' ? 'btn-primary' : 'btn-outline-primary' }}">
               الطلبات
            </a>
            <a href="{{ route('admin.premium-requests.index', array_merge(request()->except('page'), ['view' => 'users'])) }}"
               class="btn btn-sm {{ ($view ?? 'requests')==='users' ? 'btn-primary' : 'btn-outline-primary' }}">
               المستخدمون
            </a>
        </div>
    </div>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    {{-- ====== تبويب: المستخدمون ====== --}}
    @if(($view ?? 'requests') === 'users')
        <form method="GET" class="row g-2 mb-3">
            {{-- نثبت view=users في الكويري سترينغ --}}
            @foreach(request()->except(['page']) as $k=>$v)
                @if($k!=='view') <input type="hidden" name="{{ $k }}" value="{{ $v }}"> @endif
            @endforeach
            <input type="hidden" name="view" value="users">

            <div class="col-md-4">
                <input type="text" name="s" value="{{ request('s') }}" class="form-control"
                       placeholder="بحث (اسم/بريد/ID)">
            </div>
            <div class="col-md-3">
                <select name="only" class="form-select">
                    <option value="all"     @selected(request('only','all')==='all')>الكل</option>
                    <option value="premium" @selected(request('only')==='premium')>بريميوم فقط</option>
                    <option value="free"    @selected(request('only')==='free')>مجاني فقط</option>
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
                        <th>البريد</th>
                        <th class="text-center">الحالة</th>
                        <th>إنشاء</th>
                        <th>تحديث</th>
                        <th style="width:220px">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($users ?? []) as $i => $u)
                        <tr>
                            <td>{{ $users->firstItem() + $i }}</td>
                            <td>
                                {{ $u->full_name ?? $u->name ?? '—' }}
                                <small class="text-muted">(#{{ $u->id }})</small>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td class="text-center">
                                @if($u->is_premium)
                                    <span class="badge bg-success">Premium</span>
                                @else
                                    <span class="badge bg-secondary">Free</span>
                                @endif
                            </td>
                            <td>{{ $u->created_at }}</td>
                            <td>{{ $u->updated_at }}</td>
                            <td class="d-flex gap-1 flex-wrap">
                                @if(!$u->is_premium)
                                    <form method="POST" action="{{ route('admin.premium-requests.users.premium.set', $u->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success">تفعيل بريميوم</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.premium-requests.users.premium.unset', $u->id) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-warning">إلغاء بريميوم</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">لا يوجد مستخدمون.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ ($users ?? null)?->links() }}
    @else
    {{-- ====== تبويب: الطلبات (الكود الأصلي) ====== --}}

        <h4 class="mb-3">طلبات البريميوم</h4>

        <form method="GET" class="row g-2 mb-3">
            @foreach(request()->except(['page']) as $k=>$v)
                @if($k!=='view') <input type="hidden" name="{{ $k }}" value="{{ $v }}"> @endif
            @endforeach
            <input type="hidden" name="view" value="requests">

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

    @endif
</div>
@endsection
