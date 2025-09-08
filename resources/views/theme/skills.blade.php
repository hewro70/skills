@extends('theme.master')
@section('trainers-active', 'active')

@push('styles')
<style>
  /* ===== Tokens ===== */
  :root{
    --acc:#56cfe1; --ink:#0f172a; --muted:#64748b; --card:#ffffff;
    --brd:#e5e7eb; --soft:#f8fafc; --ring: 0 0 0 .25rem rgba(86,207,225,.25);
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

  /* ===== Pills (شارة المواهب) ===== */
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
  /* Locked style */
  .form-check-input:disabled + .form-check-label{
    background:#f8fafc; border-color:#e5e7eb; color:#94a3b8; cursor:not-allowed;
  }
  .form-check-label i.bi-lock-fill{ opacity:.85; vertical-align:-2px; }

  /* ===== Accordion ===== */
  .accordion-button{ font-weight:700; color:#1f2937; padding:10px 0; border:none; }
  .accordion-button:not(.collapsed){ background:transparent; color:#0e7490; box-shadow:none; }
  .accordion-item{ border:0; }
  .accordion-body .form-check{ margin-bottom:8px; }
  .accordion-body .form-check-input{ border-color:#cbd5e1; }
  .accordion-body .form-check-input:checked{ background-color:var(--acc); border-color:var(--acc); box-shadow:var(--ring); }

  /* ===== Search + Sort ===== */
  .search-container{ margin-bottom:14px; position:relative; }
  .search-container .input-group{
    background:#fff; border:1px solid var(--brd); border-radius:999px; overflow:hidden;
    box-shadow:0 6px 18px rgba(2,6,23,.03);
  }
  .search-container .form-control{ border:0; padding:.8rem 1rem; background:transparent; }
  .search-container .form-control:focus{ box-shadow:none; }
  .btn-search{ border:0; background:#fff; padding:.6rem 1rem; border-inline-start:1px solid var(--brd); }
  .btn-search i{ font-size:1.05rem; color:#0ea5b7; }
  .btn-search:hover{ background:#fdfefe; }

  .content-header{
    display:flex; justify-content:space-between; align-items:center; gap:12px;
    margin:10px 0 16px;
  }
  .content-header .results-meta{ color:var(--muted); font-size:.95rem; }
  .content-header .sort-options{ display:flex; align-items:center; gap:8px; }
  .content-header .form-select{ min-width:180px; border-radius:999px; border:1px solid var(--brd); padding:.5rem .9rem; }
  .reset-link{ color:#0e7490; text-decoration:none; font-weight:600; margin-inline-start:10px; }
  .reset-link:hover{ text-decoration:underline; }

  /* ===== Users grid ===== */
  .users-container .card{
    border:1px solid var(--brd)!important; border-radius:16px!important;
    box-shadow:0 10px 22px rgba(2,6,23,.04)!important;
    transition:transform .15s ease, box-shadow .15s ease!important;
  }
  .users-container .card:hover{ transform:translateY(-2px); box-shadow:0 16px 28px rgba(2,6,23,.08)!important; }

  /* ===== Pagination ===== */
  #pagination-links .page-link{ border-radius:10px; }
  #pagination-links .active .page-link{ background-color:var(--acc); border-color:var(--acc); }

  /* ===== Loading mask ===== */
  #results-root{ position:relative; }
  #results-root.loading::after{
    content:""; position:absolute; inset:0; background:rgba(255,255,255,.6);
  }

  /* ===== Premium lock visuals ===== */
  .is-locked .lock-hint{
    display:flex; align-items:center; gap:.5rem; color:#0e7490; font-weight:700; margin-top:8px;
  }
  .is-locked .lock-zone{
    opacity:.6;
  }
  .is-locked .lock-zone .form-control,
  .is-locked .lock-zone .form-check-input,
  .is-locked .lock-zone .form-select,
  .is-locked .lock-zone button[type=submit]{
    pointer-events:none;
  }

  /* ===== RTL tidy ===== */
  .rtl .form-check{ padding-inline-start:0; }
  /* خلّي الالتصاق للدسكتوب فقط */
@media (max-width: 991.98px){
  .skills-page .sidebar{
    position: static !important;
    top: auto !important;
  }
  /* تأكد إن البطاقة تاخذ كامل العرض وتجلس طبيعي */
  .skills-page .sidebar-card{
    position: relative;
    width: 100%;
    margin-bottom: 12px;
  }
}

/* على الدسكتوب خلي الـtop ديناميكي لو عندك هيدر ثابت */
:root { --header-h: 90px; }
.skills-page .sidebar{ top: var(--header-h); }

/* لما نعمل سكرول للنتائج، خذ بالحسبان الهيدر الثابت */
#results-root{ scroll-margin-top: calc(var(--header-h) + 8px); }

</style>
@endpush

@section('content')
<div class="skills-page rtl" dir="rtl">

  @include('theme.partials.heroSection', [
    'title'       => __('home.hero.title'),
    'description' => __('home.hero.subtitle'),
    'current'     => __('nav.skills'),
    'bgImage'     => asset('img/hero-skills.jpg'),
    'height'      => 'sm',
    'overlay'     => 'auto',
  ])

  @guest
    @include('theme.partials.register_modal', ['modalId' => 'registerModal_users'])
  @endguest

  @php
    $isPremium = auth()->check() && auth()->user()->hasActiveSubscription();
  @endphp

  <div class="container-fluid">
    {{-- نضيف class is-locked للفورم لو المستخدم مش بريميوم --}}
    <form id="filterForm"
          method="GET"
          action="{{ route('theme.skills') }}"
          data-premium="{{ $isPremium ? '1' : '0' }}"
          class="{{ $isPremium ? '' : 'is-locked' }}">
      <div class="row" id="results-root">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 sidebar">
          <div class="sidebar-card filters-card">
            <h3 class="sidebar-title">{{ __('filters.title') }}</h3>

            {{-- شارة المواهب --}}
            <div class="filter-section lock-zone">
              <h4 class="filter-subtitle">{{ __('filters.badges_title') }}</h4>
              <ul class="filter-list">
                {{-- بريميوم فقط --}}
                @if($isPremium)
                  <li>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="badge_top_plus" name="badge[]" value="top_plus"
                        {{ in_array('top_plus', (array)request('badge')) ? 'checked' : '' }}>
                      <label class="form-check-label" for="badge_top_plus">{{ __('filters.badges.top_plus') }}</label>
                    </div>
                  </li>
                @else
                  <li>
                    <div class="form-check" title="{{ __('filters.premium_only') }}">
                      <input class="form-check-input" type="checkbox" id="badge_top_plus_locked" disabled>
                      <label class="form-check-label" for="badge_top_plus_locked" style="opacity:.65">
                        <i class="bi bi-lock-fill me-1"></i> {{ __('filters.badges.top_plus') }}
                      </label>
                    </div>
                  </li>
                @endif
                {{-- داخل السايدبار، قبل/بعد أي قسم مناسب --}}


                {{-- الباقي أيضًا يتقفل إذا مش بريميوم --}}
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="badge_top" name="badge[]" value="top"
                      {{ in_array('top', (array)request('badge')) ? 'checked' : '' }} {{ $isPremium ? '' : 'disabled' }}>
                    <label class="form-check-label" for="badge_top">
                      @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                      {{ __('filters.badges.top') }}
                    </label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="badge_rising" name="badge[]" value="rising"
                      {{ in_array('rising', (array)request('badge')) ? 'checked' : '' }} {{ $isPremium ? '' : 'disabled' }}>
                    <label class="form-check-label" for="badge_rising">
                      @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                      {{ __('filters.badges.rising') }}
                    </label>
                  </div>
                </li>
                 <li>
                    <div class="form-check">
                 <input class="form-check-input" type="checkbox" id="mentor_only" name="mentor_only" value="1"
  {{ request('mentor_only') ? 'checked' : '' }} {{ $isPremium ? '' : 'disabled' }}>

                    <label class="form-check-label" for="mentor_only">
                      @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                      {{ __('filters.mentor_only') ?? 'Mentor only' }}
                    </label>
                  </div>
                  
                </li>
              </ul>

              @unless($isPremium)
                <div class="mt-2 lock-hint">
                  <i class="bi bi-stars"></i>
                  <a href="{{ route('register') }}" class="text-decoration-none">{{ __('filters.upgrade') }}</a>
                </div>
              @endunless
            </div>

            {{-- الجنس / الدولة / الفئات --}}
            <div class="accordion" id="userFilterAccordion">
              <div class="accordion-item border-0 lock-zone">
                <h2 class="accordion-header" id="headingGender">
                  <button class="accordion-button collapsed bg-white shadow-none px-0" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapseGender" aria-expanded="false"
                          aria-controls="collapseGender">
                    {{ __('filters.gender_title') }}
                  </button>
                </h2>
                <div id="collapseGender" class="accordion-collapse collapse" aria-labelledby="headingGender" data-bs-parent="#userFilterAccordion">
                  <div class="accordion-body px-0">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gender_male" name="gender[]" value="male"
                        {{ in_array('male', request('gender', [])) ? 'checked' : '' }} {{ $isPremium ? '' : 'disabled' }}>
                      <label class="form-check-label" for="gender_male">
                        @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                        {{ __('filters.gender.male') }}
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gender_female" name="gender[]" value="female"
                        {{ in_array('female', request('gender', [])) ? 'checked' : '' }} {{ $isPremium ? '' : 'disabled' }}>
                      <label class="form-check-label" for="gender_female">
                        @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                        {{ __('filters.gender.female') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item border-0 lock-zone">
                <h2 class="accordion-header" id="headingCountry">
                  <button class="accordion-button collapsed bg-white shadow-none px-0" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapseCountry" aria-expanded="false" aria-controls="collapseCountry">
                    {{ __('filters.country_title') }}
                  </button>
                </h2>
                <div id="collapseCountry" class="accordion-collapse collapse" aria-labelledby="headingCountry" data-bs-parent="#userFilterAccordion">
                  <div class="accordion-body px-0">
                    @foreach ($countries as $country)
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="country_{{ $country->id }}" name="countries[]"
                          value="{{ $country->id }}"
                          {{ in_array($country->id, request('countries', [])) ? 'checked' : '' }}
                          {{ $isPremium ? '' : 'disabled' }}>
                        <label class="form-check-label" for="country_{{ $country->id }}">
                          @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                          {{ $country->name }}
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{--  <div class="accordion-item border-0 lock-zone">
                <h2 class="accordion-header" id="headingClassification">
                  <button class="accordion-button collapsed bg-white shadow-none px-0" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapseClassification" aria-expanded="false" aria-controls="collapseClassification">
                    {{ __('filters.class_title') }}
                  </button>
                </h2>
                <div id="collapseClassification" class="accordion-collapse collapse" aria-labelledby="headingClassification" data-bs-parent="#userFilterAccordion">
                  <div class="accordion-body px-0">
                    @foreach ($classifications as $classification)
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="classification_{{ $classification->id }}"
                          name="classifications[]" value="{{ $classification->id }}"
                          {{ in_array($classification->id, request('classifications', [])) ? 'checked' : '' }}
                          {{ $isPremium ? '' : 'disabled' }}>
                        <label class="form-check-label" for="classification_{{ $classification->id }}">
                          @unless($isPremium)<i class="bi bi-lock-fill me-1"></i>@endunless
                          {{ $classification->name }}
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>  --}}
            </div>
          </div>
        </div>

        <!-- Main -->
        {{-- Main --}}
<div class="col-lg-9 col-md-8 main-content">
  @unless($isPremium)
    <div class="alert alert-info d-flex align-items-center" role="alert">
      <i class="bi bi-info-circle me-2"></i>
      <div>
        {{ __('filters.premium_only_sidebar') }}
        <a href="{{ route('register') }}" class="fw-bold text-decoration-none">{{ __('filters.upgrade') }}</a>
      </div>
    </div>
  @endunless

  <div class="search-container">
    <div class="input-group">
      <input type="text" name="search" class="form-control"
             placeholder="{{ __('filters.search_ph') }}" value="{{ request('search') }}">
      <button class="btn btn-search" type="submit">
        <i class="bi bi-search"></i>
      </button>
    </div>
  </div>

  <div class="content-header">
    <div class="results-meta">
      @php $total = $users->total() ?? count($users); @endphp
      <span>{{ __('filters.found', ['n' => $total]) }}</span>
      <a href="{{ route('theme.skills') }}" class="reset-link">{{ __('filters.reset') }}</a>
    </div>

    <div class="sort-options">
      <span>{{ __('filters.sort_title') }}</span>
      <select class="form-select" name="sort">
        <option value="relevant"  {{ request('sort') == 'relevant'  ? 'selected' : '' }}>{{ __('filters.sort.relevant') }}</option>
        <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>{{ __('filters.sort.newest') }}</option>
        <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>{{ __('filters.sort.top_rated') }}</option>
      </select>
    </div>
  </div>
  
     {{-- <div class="talent-grid"> --}}
                        <div id="users-container" class="users-container">
                            @include('theme.partials.users_grid', ['users' => $users])
                        </div>
                        <!-- Add pagination links below the talent-grid -->
<div id="pagination-links" class="mt-4">
  {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
</div>



</div>

      </div>
    </form>
  </div>
</div>

@auth
  @php
  $user = auth()->user();

  // هل بريميوم؟
  $isPremiumViewer = $user && (
      method_exists($user,'hasActiveSubscription')
        ? $user->hasActiveSubscription()
        : (bool) ($user->is_premium ?? false)
  );

  // عدّ الدعوات اللي أرسلها هذا الشهر
  $sentInvitesThisMonth = $user
      ? \App\Models\Invitation::where('source_user_id', $user->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count()
      : 0;

  // الحد المسموح للمجاني
  $freeLimit = 5;
  $remaining = max(0, $freeLimit - $sentInvitesThisMonth);
@endphp


  <div class="modal fade mt-5" id="globalInviteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="globalInviteForm" data-action="{{ route('invitations.send') }}">
        @csrf
    <div class="modal-header">
  <h5 class="modal-title" id="globalInviteTitle">{{ __('invitations.title') }}</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('invitations.cancel') }}"></button>
</div>

        <div class="modal-body">
          <input type="hidden" name="destination_user_id" id="inviteUserId">

          {{-- صندوق المجاني --}}
          {{-- صندوق المجاني --}}
  <div id="inviteFreeBox" class="d-none">
    <div class="alert alert-info d-flex align-items-center gap-2">
      <i class="bi bi-info-circle"></i>
      <div>
        {!! __('invitations.free.remaining_html', ['remaining' => $remaining, 'limit' => $freeLimit]) !!}
      </div>
    </div>
    <div class="small text-muted">
      {{-- مثال للمعاينة فقط: اسم المُرسل الحالي --}}
      {{ __('invitations.free.system_notice', ['name' => auth()->user()?->fullName()]) }}
    </div>
  </div>

          <div id="invitePremiumBox" class="d-none">
    <label class="form-label">{{ __('invitations.premium.message_label') }}</label>
    <textarea name="message" id="inviteMessage" class="form-control" rows="4" maxlength="1000"
              placeholder="{{ __('invitations.premium.message_label') }}"></textarea>
    <div class="form-text">{{ __('invitations.premium.message_help') }}</div>
  </div>
          <div class="mt-3 d-none" id="globalInviteAlert"></div>
        </div>

        <div class="modal-footer">
  <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('invitations.cancel') }}</button>
  <button type="submit" class="btn btn-primary">
    <span class="send-text">{{ __('invitations.send') }}</span>
    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
  </button>
</div>
      </form>
    </div>
  </div>
@endauth

@endsection<script>
(function(){
  const form     = document.getElementById('filterForm');
  if (!form) return;

  const root     = document.getElementById('results-root');
  const usersBox = document.getElementById('users-container');
  const pagBox   = document.getElementById('pagination-links');
  const baseUrl  = form.action; // route('theme.skills')
  const HEADERS  = { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' };
  const FETCH_TIMEOUT_MS = 7000; // failsafe

  let inflight = null; // AbortController

  function setLoading(on){ root && root.classList.toggle('loading', !!on); }
  function buildParams(extra = {}){
    const p = new URLSearchParams(new FormData(form));
    Object.entries(extra).forEach(([k,v]) => { if (v===null) p.delete(k); else p.set(k,v); });
    p.set('partial','1');
    return p;
  }

  // طلب مع تايم أوت + fallback
  async function fetchJsonWithFallback(url, fallbackUrlIfHtml) {
    if (inflight) inflight.abort();
    inflight = new AbortController();

    // تايم أوت
    const timer = setTimeout(() => inflight.abort(), FETCH_TIMEOUT_MS);

    try {
      const res = await fetch(url, { headers: HEADERS, credentials:'same-origin', signal: inflight.signal });
      const ct  = res.headers.get('Content-Type') || '';

      // لو مش JSON (HTML غالبًا): نزّل الصفحة كاملة
      if (!ct.includes('application/json')) {
        window.location.href = res.url || fallbackUrlIfHtml || url;
        return null;
      }
      const data = await res.json();
      if (!res.ok || !data?.ok) throw new Error('Bad JSON');
      return data;
    } finally {
      clearTimeout(timer);
      inflight = null;
    }
  }

  async function load(urlOrParams, push=false){
    setLoading(true);
    try{
      const url = typeof urlOrParams === 'string'
        ? urlOrParams
        : (baseUrl + '?' + urlOrParams.toString());

      const data = await fetchJsonWithFallback(url, url);
      if (!data) return; // تم التحويل الكامل

      // حدّث الشبكة
      if (data.users_html)      usersBox.innerHTML = data.users_html;
      if (data.pagination_html) pagBox.innerHTML   = data.pagination_html;

      // نظّف الرابط الظاهر
      const clean = (data.url || url).replace(/([?&])partial=1(&|$)/,'$1').replace(/[?&]$/,'');
      push ? history.pushState(null,'', clean) : history.replaceState(null,'', clean);

      // لو السيرفر صحّح الصفحة (page>last) حمّل الرابط المصحّح مرة ثانية (جزئي)
      if (data.url && data.url !== url) {
        const u = new URL(clean, window.location.href);
        u.searchParams.set('partial', '1');
        await load(u.toString(), false);
        return;
      }

      // سكروول خفيف لبداية النتائج
      root.scrollIntoView({ behavior:'smooth', block:'start' });
    } catch (e){
      // فشل غريب؟ نزّل الصفحة كاملة كحل أخير
      try {
        const loc = typeof urlOrParams === 'string' ? urlOrParams : (baseUrl + '?' + urlOrParams.toString());
        const fallback = (loc + (loc.includes('?') ? '&' : '?')).replace(/[?&]partial=1(&|$)/,'$1');
        window.location.href = fallback;
      } catch(_) {}
    } finally {
      setLoading(false);
    }
  }

  // ===== الأحداث =====

  // الفرز
  form.querySelector('select[name="sort"]')?.addEventListener('change', ()=>{
    load(buildParams({ page:null }), true);
  });

  // البحث (debounce) + منع submit العادي
  let t;
  const search = form.querySelector('input[name="search"]');
  if (search){
    search.addEventListener('input', ()=>{
      clearTimeout(t);
      t = setTimeout(()=> load(buildParams({ page:null }), false), 450);
    });
    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      load(buildParams({ page:null }), true);
    });
  }

  // أي تغيير لاحق بالفورم يصفّر الصفحة
  form.addEventListener('change', (e)=>{
    const n = e.target?.name || '';
    if (n && n !== 'search' && n !== 'sort') load(buildParams({ page:null }), true);
  });

  // الباجيناشن (تفويض) — استخدم a.href المطلق
  document.addEventListener('click', function(ev){
    const a = ev.target.closest('#pagination-links a');
    if (!a) return;

    const href = a.href;
    if (!href || href === '#' || href.startsWith('javascript:')) return;

    ev.preventDefault();

    const u = new URL(href, window.location.href); // مهم: base = location.href
    u.searchParams.set('partial', '1');
    load(u.toString(), true);
  });

  // back/forward
  window.addEventListener('popstate', ()=>{
    const u = new URL(window.location.href);
    u.searchParams.set('partial','1');
    load(u.toString(), false);
  });

})();
</script>
