@extends('layout.admin')

@section('content')
<div class="container">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

   {{-- ====== Styles للكاردز (خفيف وسريع) ====== --}}
<style>
    .stat-card{ border:0; border-radius:1rem; transition:transform .15s ease, box-shadow .15s ease; }
    .stat-card:hover{ transform:translateY(-2px); box-shadow:0 .5rem 1rem rgba(0,0,0,.12)!important; }
    .stat-card .stat-icon{
        width:54px;height:54px; display:inline-flex; align-items:center; justify-content:center;
        border-radius:14px; background:rgba(255,255,255,.18);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.25);
    }
    .stat-card h3{ font-weight:800; letter-spacing:.3px; margin: .25rem 0 0; }
    .stat-sub{ opacity:.9; font-size:.9rem; }
</style>

{{-- Dashboard Cards (موحّدة) --}}
@php
    $cards = [
        ['title'=>'إجمالي المستخدمين',      'value'=>$totalUsers,              'icon'=>'people-fill',          'bg'=>'primary'],
        ['title'=>'مستخدمين هذا الأسبوع',     'value'=>$newUsersToday,           'icon'=>'person-plus-fill',     'bg'=>'success'],
        ['title'=>'عدد الدعوات المرسلة',      'value'=>$sentInvitationsCount,    'icon'=>'envelope-paper-fill',  'bg'=>'warning', 'text'=>'dark'],
        ['title'=>'عدد الدعوات المقبولة',     'value'=>$acceptedInvitationsCount,'icon'=>'envelope-check-fill',  'bg'=>'info',    'text'=>'dark'],
        ['title'=>'عدد التبادلات التي بدأت',  'value'=>$startedExchangesCount,   'icon'=>'play-circle-fill',     'bg'=>'success'],
        ['title'=>'عدد التبادلات التي انتهت', 'value'=>$endExchangesCount,       'icon'=>'stop-circle-fill',     'bg'=>'danger'],
        ['title'=>'نسبة المحادثات الفعّالة',  'value'=>($activeChatRate.'%'),    'icon'=>'percent',              'bg'=>'info',    'text'=>'dark',
            'sub'=> $activeChats.' من أصل '.$totalChats.' محادثة'],
        ['title'=>'عدد المحادثات',           'value'=>$totalChats,              'icon'=>'chat-dots-fill',       'bg'=>'secondary'],
        ['title'=>'متوسط التقييمات',         'value'=>number_format($averageRating,2).' / 5','icon'=>'star-fill','bg'=>'primary'],
        ['title'=>'عدد المراجعات المكتوبة',  'value'=>$writtenReviewsCount,     'icon'=>'chat-left-text-fill',  'bg'=>'primary'],
    ];
@endphp

<div class="row g-4 mb-4">
@foreach($cards as $c)
    @php
        $bg   = $c['bg'] ?? 'primary';
        $tcol = $c['text'] ?? 'white';
    @endphp
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card text-{{ $tcol }} bg-{{ $bg }} shadow-sm h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <div class="stat-icon mx-auto mb-2">
                    <i class="bi bi-{{ $c['icon'] }} fs-3"></i>
                </div>
                <h6 class="mb-1 fw-semibold">{{ $c['title'] }}</h6>
                <h3 class="fw-bold">{{ $c['value'] }}</h3>
                @isset($c['sub'])
                    <div class="stat-sub mt-1">{{ $c['sub'] }}</div>
                @endisset
            </div>
        </div>
    </div>
@endforeach
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
