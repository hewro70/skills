@php
  $actionRoute = route('theme.index');
  $resetUrl    = route('theme.index');
@endphp

@push('styles')
<style>
  /* ===== Layout ===== */
  .filters-wrap{ --card-bg:#fff; --muted:#6b7280; }
  .filters-card{
    background:var(--card-bg);
    border:1px solid #eef2f7; border-radius:16px; padding:16px;
    box-shadow:0 8px 24px rgba(0,0,0,.05);
    position:sticky; top:88px;
  }
  .filters-card .sidebar-title{
    display:flex; align-items:center; justify-content:space-between;
    font-weight:700; font-size:1.05rem; margin-bottom:.5rem;
  }
  .filters-card .hint{ color:var(--muted); font-size:.85rem; }

  /* Section blocks */
  .filter-section{ padding:12px 0; border-top:1px dashed #eef2f7; }
  .filter-section:first-of-type{ border-top:0; padding-top:0; }
  .filter-subtitle{
    display:flex; align-items:center; gap:.5rem;
    font-size:.95rem; font-weight:700; margin:0 0 .35rem;
  }
  .filter-subtitle .bi{ opacity:.75; }

  /* Pills (checkboxes) */
  .filter-list{ list-style:none; padding:0; margin:0; display:flex; flex-wrap:wrap; gap:.5rem; }
  .filter-list .form-check{ padding:0; margin:0; }
  .filter-list .form-check-input{ display:none; }
  .filter-list .form-check-label{
    display:inline-flex; align-items:center; gap:.35rem; cursor:pointer;
    padding:.45rem .75rem; border:1px solid #e9eef5; border-radius:999px; background:#f8fafc;
    font-size:.9rem; color:#334155; transition:.15s ease;
  }
  .filter-list .form-check-input:checked + .form-check-label{
    background:#eef6ff; color:#0d6efd; border-color:#cfe4ff;
    box-shadow:0 4px 14px rgba(13,110,253,.12);
  }

  /* Accordion */
  .accordion-button{
    padding:0; font-weight:700; color:#0f172a !important;
  }
  .accordion-button::after{ margin-inline-start:auto; }
  .accordion-body .form-check{ margin:.35rem 0; }
  .accordion-body .form-check-label{ color:#334155; }

  /* Actions */
  .filter-actions .btn{ border-radius:12px; }

  /* ===== Main Header ===== */
  .content-header{
    display:flex; align-items:center; justify-content:space-between;
    gap:1rem; background:#fff; border:1px solid #eef2f7; border-radius:16px;
    padding:12px 16px; box-shadow:0 8px 24px rgba(0,0,0,.04); margin-bottom:12px;
  }
  .results-meta{ color:#334155; font-weight:600; }
  .sort-options{ display:flex; align-items:center; gap:.5rem; }
  .sort-options .form-select{
    width:auto; min-width:180px; border-radius:12px; border-color:#e8eef6;
  }

  /* Active chips */
  .active-chips{ display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:10px; }
  .chip-active{
    display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .6rem;
    font-size:.85rem; border-radius:999px; background:#f1f5f9; border:1px solid #e2e8f0; color:#0f172a;
  }
  .chip-active i{ cursor:pointer; opacity:.7; }
  @media (max-width: 991px){ .filters-card{ position:static; } }
</style>
@endpush

<div class="container-fluid filters-wrap">
  <form id="filterForm" method="GET" action="{{ $actionRoute }}">
    {{-- احتفاظ بقيمة البحث التي جاءت من الهيرو --}}
    <input type="hidden" name="search" value="{{ request('search') }}">

    <div class="row g-4">
      <!-- Sidebar -->
      <div class="col-lg-3 col-md-4">
        <div class="filters-card">
          <div class="sidebar-title">
            <span>الفلاتر</span>
            <a href="{{ $resetUrl }}" class="btn btn-sm btn-outline-secondary">إعادة التعيين</a>
          </div>
          <div class="hint">اختر ما يلزم وسيتم تطبيقه مباشرةً.</div>

          {{-- شارة المواهب --}}
          <div class="filter-section">
            <h4 class="filter-subtitle"><i class="bi bi-award"></i> شارة المواهب</h4>
            <ul class="filter-list">
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="badge1" name="badges[]" value="top_plus"
                         {{ in_array('top_plus', request('badges', [])) ? 'checked' : '' }}>
                  <label class="form-check-label" for="badge1"><i class="bi bi-stars"></i> الأعلى بلس</label>
                </div>
              </li>
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="badge2" name="badges[]" value="top"
                         {{ in_array('top', request('badges', [])) ? 'checked' : '' }}>
                  <label class="form-check-label" for="badge2"><i class="bi bi-trophy"></i> الأعلى تقييماً</label>
                </div>
              </li>
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="badge3" name="badges[]" value="rising"
                         {{ in_array('rising', request('badges', [])) ? 'checked' : '' }}>
                  <label class="form-check-label" for="badge3"><i class="bi bi-graph-up-arrow"></i> موهبة صاعدة</label>
                </div>
              </li>
            </ul>
          </div>

          {{-- Accordion --}}
          <div class="accordion" id="userFilterAccordion">
            <div class="accordion-item border-0 filter-section">
              <h2 class="accordion-header" id="headingGender">
                <button class="accordion-button collapsed bg-white shadow-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseGender" aria-controls="collapseGender">
                  الجنس
                </button>
              </h2>
              <div id="collapseGender" class="accordion-collapse collapse" data-bs-parent="#userFilterAccordion">
                <div class="accordion-body px-0">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gender_male" name="gender[]" value="male"
                           {{ in_array('male', request('gender', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="gender_male">ذكر</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gender_female" name="gender[]" value="female"
                           {{ in_array('female', request('gender', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="gender_female">أنثى</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 filter-section">
              <h2 class="accordion-header" id="headingCountry">
                <button class="accordion-button collapsed bg-white shadow-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseCountry" aria-controls="collapseCountry">
                  الدولة
                </button>
              </h2>
              <div id="collapseCountry" class="accordion-collapse collapse" data-bs-parent="#userFilterAccordion">
                <div class="accordion-body px-0">
                  @foreach ($countries as $country)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox"
                             id="country_{{ $country->id }}" name="countries[]"
                             value="{{ $country->id }}"
                             {{ in_array($country->id, request('countries', [])) ? 'checked' : '' }}>
                      <label class="form-check-label" for="country_{{ $country->id }}">{{ $country->name }}</label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 filter-section">
              <h2 class="accordion-header" id="headingClassification">
                <button class="accordion-button collapsed bg-white shadow-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseClassification" aria-controls="collapseClassification">
                  الفئات
                </button>
              </h2>
              <div id="collapseClassification" class="accordion-collapse collapse" data-bs-parent="#userFilterAccordion">
                <div class="accordion-body px-0">
                  @foreach ($classifications as $classification)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox"
                             id="classification_{{ $classification->id }}"
                             name="classifications[]" value="{{ $classification->id }}"
                             {{ in_array($classification->id, request('classifications', [])) ? 'checked' : '' }}>
                      <label class="form-check-label" for="classification_{{ $classification->id }}">
                        {{ $classification->name }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="filter-actions mt-3">
            <a href="{{ $resetUrl }}" class="btn btn-outline-secondary w-100">إعادة التعيين</a>
          </div>
        </div>
      </div>

      <!-- Main -->
      <div class="col-lg-9 col-md-8">
        {{-- شرائط الفلاتر المفعّلة --}}
        <div class="active-chips">
          @foreach(request('badges', []) as $b)
            <span class="chip-active">شارة: {{ $b }} <a href="{{ request()->fullUrlWithQuery(['badges'=>collect(request('badges'))->reject(fn($x)=>$x===$b)->values()->all()]) }}"><i class="bi bi-x"></i></a></span>
          @endforeach
          @foreach(request('gender', []) as $g)
            <span class="chip-active">الجنس: {{ $g }} <a href="{{ request()->fullUrlWithQuery(['gender'=>collect(request('gender'))->reject(fn($x)=>$x===$g)->values()->all()]) }}"><i class="bi bi-x"></i></a></span>
          @endforeach
          @foreach(request('countries', []) as $c)
            @php $cn = optional($countries->firstWhere('id',$c))->name; @endphp
            <span class="chip-active">الدولة: {{ $cn ?? $c }} <a href="{{ request()->fullUrlWithQuery(['countries'=>collect(request('countries'))->reject(fn($x)=>$x==$c)->values()->all()]) }}"><i class="bi bi-x"></i></a></span>
          @endforeach
          @foreach(request('classifications', []) as $cl)
            @php $cln = optional($classifications->firstWhere('id',$cl))->name; @endphp
            <span class="chip-active">الفئة: {{ $cln ?? $cl }} <a href="{{ request()->fullUrlWithQuery(['classifications'=>collect(request('classifications'))->reject(fn($x)=>$x==$cl)->values()->all()]) }}"><i class="bi bi-x"></i></a></span>
          @endforeach
        </div>

        <div class="content-header">
          <div class="results-meta">
            @php $total = method_exists($users,'total') ? $users->total() : (is_countable($users) ? count($users) : 0); @endphp
            وجدنا {{ $total }} نتيجة
          </div>
          <div class="sort-options">
            <span>ترتيب حسب:</span>
            <select class="form-select" name="sort" onchange="this.form.requestSubmit()">
              <option value="relevant"  {{ request('sort') == 'relevant' ? 'selected' : '' }}>الأكثر صلة</option>
              <option value="newest"    {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
              <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>الأعلى تقييمًا</option>
            </select>
          </div>
        </div>

        <div id="users-container" class="users-container">
          @include('theme.partials.users_grid', ['users' => $users])
        </div>

        <div id="pagination-links" class="mt-4">
          {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </form>
</div>

@push('scripts')
<script>
  (function(){
    const form = document.getElementById('filterForm');
    if(!form) return;
    const inputs = form.querySelectorAll('input[type="checkbox"], select[name="sort"]');
    inputs.forEach(el => el.addEventListener('change', () => form.requestSubmit()));
  })();
</script>
@endpush
