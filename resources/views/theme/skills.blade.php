@extends('theme.master')
@section('trainers-active', 'active')
@push('styles')
<style>
  /* ===== Tokens ===== */
  :root{
    --acc:#56cfe1;           /* سماوي هادئ */
    --ink:#0f172a;           /* نص داكن */
    --muted:#64748b;         /* نص خافت */
    --card:#ffffff;
    --brd:#e5e7eb;
    --soft:#f8fafc;
    --ring: 0 0 0 .25rem rgba(86,207,225,.25);
  }

  .skills-page{ background:var(--soft); }

  /* ===== Layout ===== */
  .skills-page .sidebar{ position:sticky; top:90px; align-self:flex-start; }
  .skills-page .sidebar-card{
    background:var(--card); border:1px solid var(--brd);
    border-radius:16px; padding:18px; box-shadow:0 6px 18px rgba(2,6,23,.04);
  }
  .skills-page .filters-card .sidebar-title{
    font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:8px;
  }
  .skills-page .filter-section{ margin:14px 0 8px; }
  .skills-page .filter-subtitle{
    font-size:.95rem; font-weight:700; color:var(--muted); margin-bottom:8px;
  }

  /* ===== Pills for "شارة المواهب" ===== */
  .filter-list{ display:flex; flex-wrap:wrap; gap:8px; padding:0; margin:0; list-style:none; }
  .filter-list .form-check{ margin:0; }
  .filter-list .form-check-input{ display:none; }
  .filter-list .form-check-label{
    display:inline-block; padding:.42rem .72rem; border:1px solid var(--brd);
    border-radius:999px; background:#fff; color:#334155; font-weight:600; font-size:.9rem;
    cursor:pointer; transition:.2s ease;
  }
  .filter-list .form-check-input:checked + .form-check-label{
    background:rgba(86,207,225,.12); border-color:#a5e9f2; color:#0e7490;
    box-shadow: inset 0 0 0 1px rgba(86,207,225,.35);
  }

  /* ===== Accordion (فئات/دولة/جنس) ===== */
  .accordion-button{
    font-weight:700; color:#1f2937; padding:10px 0; border:none;
  }
  .accordion-button:not(.collapsed){ background:transparent; color:#0e7490; box-shadow:none; }
  .accordion-item{ border:0; }
  .accordion-body .form-check{ margin-bottom:8px; }
  .accordion-body .form-check-input{
    border-color:#cbd5e1;
  }
  .accordion-body .form-check-input:checked{
    background-color:var(--acc); border-color:var(--acc);
    box-shadow:var(--ring);
  }

  /* ===== Search + Sort Bar ===== */
  .search-container{ margin-bottom:14px; }
  .search-container .input-group{
    background:#fff; border:1px solid var(--brd); border-radius:999px; overflow:hidden;
    box-shadow:0 6px 18px rgba(2,6,23,.03);
  }
  .search-container .form-control{
    border:0; padding:.8rem 1rem; background:transparent;
  }
  .search-container .form-control:focus{ box-shadow:none; }
  .btn-search{
    border:0; background:#fff; padding:.6rem 1rem; border-inline-start:1px solid var(--brd);
  }
  .btn-search i{ font-size:1.05rem; color:#0ea5b7; }
  .btn-search:hover{ background:#fdfefe; }

  .content-header{
    display:flex; justify-content:space-between; align-items:center; gap:12px;
    margin:10px 0 16px;
  }
  .content-header .results-meta{
    color:var(--muted); font-size:.95rem;
  }
  .content-header .sort-options{
    display:flex; align-items:center; gap:8px;
  }
  .content-header .form-select{
    min-width:180px; border-radius:999px; border:1px solid var(--brd); padding:.5rem .9rem;
  }
  .reset-link{
    color:#0e7490; text-decoration:none; font-weight:600; margin-inline-start:10px;
  }
  .reset-link:hover{ text-decoration:underline; }

  /* ===== Users grid (تنظيف عام) ===== */
  .users-container .card{
    border:1px solid var(--brd)!important; border-radius:16px!important;
    box-shadow:0 10px 22px rgba(2,6,23,.04)!important;
    transition:transform .15s ease, box-shadow .15s ease!important;
  }
  .users-container .card:hover{
    transform:translateY(-2px);
    box-shadow:0 16px 28px rgba(2,6,23,.08)!important;
  }

  /* ===== Pagination ===== */
  #pagination-links .page-link{ border-radius:10px; }
  #pagination-links .active .page-link{
    background-color:var(--acc); border-color:var(--acc);
  }

  /* ===== RTL tidy ===== */
  .rtl .form-check{ padding-inline-start:0; }
</style>
@endpush

@push('scripts')
<script>
  (function(){
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('input[type="checkbox"], select[name="sort"]');
    const search = form.querySelector('input[name="search"]');

    // Submit عند تغيير أي فلتر
    inputs.forEach(el=>{
      el.addEventListener('change', ()=> form.requestSubmit());
    });

    // Debounce للبحث
    let t;
    search && search.addEventListener('input', ()=>{
      clearTimeout(t);
      t = setTimeout(()=> form.requestSubmit(), 450);
    });
  })();
</script>
@endpush

@section('content')
    <div class="skills-page rtl" dir="rtl">

        @include('theme.partials.heroSection', [
            'title' => 'مهارات',
            'description' => 'منصة لتبادل المهارات بين الاشخاص',
            'current' => 'مهارات',
        ])

        <div class="container-fluid">
            <form id="filterForm" method="GET" action="{{ route('theme.skills') }}">
                <div class="row">
                    <!-- Sidebar Column -->
                    <div class="col-lg-3 col-md-4 sidebar">
                        <div class="sidebar-card filters-card">
                            <h3 class="sidebar-title">الفلاتر</h3>
                            <div class="filter-section">
                                <h4 class="filter-subtitle">شارة المواهب</h4>
                                <ul class="filter-list">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="badge1">
                                            <label class="form-check-label" for="badge1">الأعلى تقييماً بلس</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="badge2">
                                            <label class="form-check-label" for="badge2">الأعلى تقييماً</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="badge3">
                                            <label class="form-check-label" for="badge3">موهبة صاعدة</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="accordion" id="userFilterAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingGender">
                                        <button class="accordion-button collapsed bg-white shadow-none px-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseGender" aria-expanded="false"
                                            aria-controls="collapseGender">
                                            الجنس
                                        </button>
                                    </h2>
                                    <div id="collapseGender" class="accordion-collapse collapse"
                                        aria-labelledby="headingGender" data-bs-parent="#userFilterAccordion">
                                        <div class="accordion-body px-0">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gender_male"
                                                    name="gender[]" value="male"
                                                    {{ in_array('male', request('gender', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_male">ذكر</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gender_female"
                                                    name="gender[]" value="female"
                                                    {{ in_array('female', request('gender', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_female">انثى</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingCountry">
                                        <button class="accordion-button collapsed bg-white shadow-none px-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseCountry"
                                            aria-expanded="false" aria-controls="collapseCountry">
                                            الدولة
                                        </button>
                                    </h2>
                                    <div id="collapseCountry" class="accordion-collapse collapse"
                                        aria-labelledby="headingCountry" data-bs-parent="#userFilterAccordion">
                                        <div class="accordion-body px-0">
                                            @foreach ($countries as $country)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="country_{{ $country->id }}" name="countries[]"
                                                        value="{{ $country->id }}"
                                                        {{ in_array($country->id, request('countries', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="country_{{ $country->id }}">
                                                        {{ $country->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingClassification">
                                        <button class="accordion-button collapsed bg-white shadow-none px-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseClassification"
                                            aria-expanded="false" aria-controls="collapseClassification">
                                            الفئات
                                        </button>
                                    </h2>
                                    <div id="collapseClassification" class="accordion-collapse collapse"
                                        aria-labelledby="headingClassification" data-bs-parent="#userFilterAccordion">
                                        <div class="accordion-body px-0">
                                            @foreach ($classifications as $classification)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="classification_{{ $classification->id }}"
                                                        name="classifications[]" value="{{ $classification->id }}"
                                                        {{ in_array($classification->id, request('classifications', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="classification_{{ $classification->id }}">{{ $classification->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="filter-actions mt-3">
                                <a href="{{ route('theme.skills') }}" class="btn btn-outline-secondary w-100 mt-2">إعادة
                                    تعيين</a>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Main Content Column -->
                    <div class="col-lg-9 col-md-8 main-content">
                        <div class="search-container">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث عن المهارات أو المواهب..." value="{{ request('search') }}">
                                <button class="btn btn-search" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

              <div class="content-header">
  <div class="results-meta">
    @php $total = $users->total() ?? count($users); @endphp
    <span>وجدنا {{ $total }} نتيجة</span>
    <a href="{{ route('theme.skills') }}" class="reset-link">إعادة التعيين</a>
  </div>
  <div class="sort-options">
    <span>ترتيب حسب:</span>
    <select class="form-select" name="sort" onchange="this.form.requestSubmit()">
      <option value="relevant" {{ request('sort') == 'relevant' ? 'selected' : '' }}>الأكثر صلة</option>
      <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
      <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>الأعلى تقييمًا</option>
    </select>
  </div>
</div>


                        {{-- <div class="talent-grid"> --}}
                        <div id="users-container" class="users-container">
                            @include('theme.partials.users_grid', ['users' => $users])
                        </div>

                        <!-- Add pagination links below the talent-grid -->
                        <div id="pagination-links" class="mt-4">
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
