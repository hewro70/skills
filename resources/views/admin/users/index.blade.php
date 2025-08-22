@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-5 rounded-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="bi bi-people-fill me-2"></i> قائمة المستخدمين
            </h5>
        </div>

        <div class="card-body table-responsive">
            @if($users->count())
                <table class="table table-hover table-bordered text-center align-middle">
                    <thead class="table-dark">
                        <tr class="fw-semibold">
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>الجنس</th>
                            <th>تاريخ الميلاد</th>
                            <th>تاريخ التسجيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td class="fw-bold">{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $user->gender == 'male' ? 'info' : 'warning' }}">
                                    {{ $user->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                </span>
                            </td>
                            <td>{{ $user->date_of_birth ?? '—' }}</td>

                            <td><span class="text-muted">{{ $user->created_at->format('Y-m-d') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info text-center mb-0">
                    <i class="bi bi-info-circle me-2"></i> لا يوجد مستخدمون حاليا.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
