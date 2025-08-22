@extends('layout.admin')

@section('content')
<div class="container">

    {{-- Dashboard Cards --}}
    <div class="row g-4 mb-4">
        <!-- إجمالي المستخدمين -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1 mb-2"></i>
                    <h5 class="card-title">إجمالي المستخدمين</h5>
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>

        <!-- مستخدمين هذا الأسبوع -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-plus-fill fs-1 mb-2"></i>
                    <h5 class="card-title">مستخدمين هذا الأسبوع</h5>
                    <h3>{{ $newUsersToday }}</h3>
                </div>
            </div>
        </div>

        <!-- عدد الدعوات المرسلة -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-envelope-paper-fill fs-1 mb-2"></i>
                    <h5 class="card-title">عدد الدعوات المرسلة</h5>
                    <h3>{{ $sentInvitationsCount }}</h3>
                </div>
            </div>
        </div>

        <!-- عدد الدعوات المقبولة -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-envelope-check-fill fs-1 mb-2"></i>
                    <h5 class="card-title">عدد الدعوات المقبولة</h5>
                    <h3>{{ $acceptedInvitationsCount }}</h3>
                </div>
            </div>
        </div>

        <!-- عدد التبادلات التي بدأت -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-play-circle-fill fs-1 mb-2"></i>
                    <h5 class="card-title">عدد التبادلات التي بدأت</h5>
                    <h3>{{ $startedExchangesCount }}</h3>
                </div>
            </div>
        </div>

        <!-- عدد التبادلات التي انتهت -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-danger shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-stop-circle-fill fs-1 mb-2"></i>
                    <h5 class="card-title">عدد التبادلات التي انتهت</h5>
                    <h3>{{ $endExchangesCount }}</h3>
                </div>
            </div>
        </div>

        <!-- نسبة المحادثات الفعّالة -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-percent fs-1 mb-2"></i>
                    <h5 class="card-title">نسبة المحادثات الفعّالة</h5>
                    <h3>{{ $activeChatRate }}%</h3>
                    <small>{{ $activeChats }} من أصل {{ $totalChats }} محادثة</small>
                </div>
            </div>
        </div>

        <!-- عدد المحادثات -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-secondary shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-chat-dots-fill fs-1 mb-3"></i>
                    <h5 class="card-title mb-1">عدد المحادثات</h5>
                    <h3 class="fw-bold">{{ $totalChats }}</h3>
                </div>
            </div>
        </div>

        <!-- متوسط التقييمات -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill fs-1 mb-2"></i>
                    <h5 class="card-title">متوسط التقييمات</h5>
                    <h3 class="fw-bold">{{ number_format($averageRating, 2) }} / 5</h3>
                </div>
            </div>
        </div>

        <!-- عدد المراجعات المكتوبة -->
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-chat-left-text-fill fs-1 mb-2"></i>
                    <h5 class="card-title">عدد المراجعات المكتوبة</h5>
                    <h3 class="fw-bold">{{ $writtenReviewsCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>الإحصائيات البيانية</h5>
        </div>
        <div class="card-body">
            <canvas id="statsChart" height="150"></canvas>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-funnel-fill me-2"></i>تصفية المستخدمين حسب الدولة والجنس</h5>
        </div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label for="countryFilter" class="form-label">الدولة:</label>
                <select id="countryFilter" class="form-select">
                    <option value="">-- اختر الدولة --</option>
                    @foreach($usersByCountry as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <div id="countryResult" class="mt-2 text-primary fw-bold"></div>
            </div>

            <div class="col-md-6">
                <label for="genderFilter" class="form-label">الجنس:</label>
                <select id="genderFilter" class="form-select">
                    <option value="">-- اختر الجنس --</option>
                    @foreach($usersByGender as $item)
                        <option value="{{ $item->gender }}">{{ $item->gender }}</option>
                    @endforeach
                </select>
                <div id="genderResult" class="mt-2 text-primary fw-bold"></div>
            </div>
        </div>
    </div>

    {{-- Top Skills Section --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i>أكثر 5 مهارات متبادلة</h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($topSkills as $skill)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-lightning-fill text-warning me-2"></i> {{ $skill->name }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted">لا توجد مهارات مسجلة</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center text-muted py-3 border-top mt-5">
        © {{ date('Y') }} جميع الحقوق محفوظة - Maharat Hub
    </footer>

</div>

{{-- jQuery + Chart.js --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart config
    const ctx = document.getElementById('statsChart').getContext('2d');
    const statsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['مستخدمون', 'دعوات مرسلة', 'دعوات مقبولة', 'بدأت', 'انتهت', 'محادثات', 'فعالة', 'مراجعات'],
            datasets: [{
                label: 'الإحصائيات الرئيسية',
                data: [
                    {{ $totalUsers }},
                    {{ $sentInvitationsCount }},
                    {{ $acceptedInvitationsCount }},
                    {{ $startedExchangesCount }},
                    {{ $endExchangesCount }},
                    {{ $totalChats }},
                    {{ $activeChats }},
                    {{ $writtenReviewsCount }}
                ],
                backgroundColor: [
                    '#0d6efd', '#ffc107', '#0dcaf0', '#198754', '#dc3545', '#6c757d', '#20c997', '#6610f2'
                ]
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: { color: '#333', font: { size: 14 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#333', font: { size: 14 } }
                }
            }
        }
    });

    // AJAX filter handlers
    $('#countryFilter').on('change', function () {
        const countryId = $(this).val();
        if (countryId) {
            $.post('{{ route('admin.filter.country') }}', {
                country_id: countryId,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $('#countryResult').html(`عدد المستخدمين في هذه الدولة: <strong>${data.count}</strong>`);
            });
        } else {
            $('#countryResult').empty();
        }
    });

    $('#genderFilter').on('change', function () {
        const gender = $(this).val();
        if (gender) {
            $.post('{{ route('admin.filter.gender') }}', {
                gender: gender,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $('#genderResult').html(`عدد المستخدمين من هذا الجنس: <strong>${data.count}</strong>`);
            });
        } else {
            $('#genderResult').empty();
        }
    });
</script>

@endsection
