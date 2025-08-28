@extends('theme.master')
@section('index-active', 'active')

@section('content')
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">

  {{-- ================== HERO ================== --}}
  <section class="home-hero position-relative overflow-hidden">
    <img class="hero-bg" src="{{ asset('img/hero.jpg') }}" alt="خلفية مهارات هب">

    <div class="container position-relative">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10 text-center">
          <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm">
           {{ __('home.hero.badge') }}
          </span>

<h1 class="display-4 fw-bolder mb-3" style="color:#fff;">{{ __('home.hero.title') }}</h1>
<p class="lead text-white-75 mb-4">{{ __('home.hero.subtitle') }}</p>

          {{-- ===== HERO SEARCH (search-as-you-type) ===== --}}
          <form id="heroForm" action="{{ route('theme.index') }}" method="GET" class="hero-search" role="search" autocomplete="off" aria-label="{{ __('home.hero.search.aria') }}">
            <div class="field">
              <span class="icon-badge"><i class="bi bi-search"></i></span>
              <input type="text"
                    name="search"
                    id="search-input"
                    class="form-control"
                    placeholder="{{ __('home.hero.search.placeholder') }}"
                    value="{{ request('search') }}"
                    list="skillsList"
                    aria-describedby="searchHelp">
              <datalist id="skillsList">
                @isset($skills)
                  @foreach($skills as $s)
                    <option value="{{ $s->name }}"></option>
                  @endforeach
                @endisset
              </datalist>
            </div>

            <div class="select-wrap">
              @php $currType = request('type'); @endphp
              <select name="type" class="form-select" aria-label="{{ __('home.hero.select.aria') }}" id="hero-type">
    <option value="" {{ $currType==='' || $currType===null ? 'selected' : '' }}>{{ __('home.hero.select.all') }}</option>
    <option value="language" {{ $currType==='language' ? 'selected' : '' }}>{{ __('home.hero.select.language') }}</option>
    <option value="tech"     {{ $currType==='tech' ? 'selected' : '' }}>{{ __('home.hero.select.tech') }}</option>
    <option value="music"    {{ $currType==='music' ? 'selected' : '' }}>{{ __('home.hero.select.music') }}</option>
    <option value="art"      {{ $currType==='art' ? 'selected' : '' }}>{{ __('home.hero.select.art') }}</option>
    <option value="academic" {{ $currType==='academic' ? 'selected' : '' }}>{{ __('home.hero.select.academic') }}</option>
  </select>
            </div>

            <button type="submit" class="btn btn-search">
              <i class="bi bi-search"></i>
              <span>{{ __('common.search') }}</span>
            </button>
          </form>

          {{-- شرائط الفلاتر المفعّلة (AJAX) --}}
          <div id="active-chips" class="active-chips">
            @include('theme.partials.active_chips', compact('countries','classifications'))
          </div>

          {{-- اقتراحات سريعة --}}
          <div class="chips mt-3" aria-label="{{ __('home.hero.quick.aria') }}">
            @php $quick = isset($popularSkills) && count($popularSkills) ? $popularSkills->take(8)->pluck('name')->toArray() : ['اللغة الإنجليزية','برمجة','تصميم','جيتار','طبخ','رياضيات','تسويق رقمي','تصوير']; @endphp
            @foreach ($quick as $q)
              <button type="button" class="chip js-fill" data-value="{{ $q }}">#{{ $q }}</button>
            @endforeach
          </div>

          {{-- إحصائيات شكلية --}}
          <div class="row g-3 mt-4 justify-content-center">
            <div class="col-4 col-md-3">
              <div class="glass rounded-3 py-3">
                <div class="fs-4 fw-bold">{{ isset($popularSkills) ? $popularSkills->count() : 12 }}</div>
                <div class="small text-white-75">{{ __('home.hero.stats.popular') }}</div>
              </div>
            </div>
            <div class="col-4 col-md-3">
              <div class="glass rounded-3 py-3">
                <div class="fs-4 fw-bold">{{ isset($skills) ? $skills->count() : 120 }}</div>
                <div class="small text-white-75">{{ __('home.hero.stats.skills_available') }}</div>
              </div>
            </div>
            <div class="col-4 col-md-3">
              <div class="glass rounded-3 py-3">
                <div class="fs-4 fw-bold">+99</div>
                <div class="small text-white-75">{{ __('home.hero.stats.sessions_done') }}</div>
              </div>
            </div>
          </div>

          <small id="searchHelp" class="d-block mt-3 text-white-50">
        {{ __('home.hero.search.help') }}
          </small>
        </div>
      </div>
    </div>
  </section>

  {{-- ================== FILTERS + RESULTS ================== --}}
  <div class="container-fluid filters-wrap">
    <form id="filterForm" method="GET" action="{{ route('theme.index') }}">
      {{-- تزامُن قيم الهيرو --}}
      <input type="hidden" name="search" id="hidden-search" value="{{ request('search') }}">
      <input type="hidden" name="type"   id="hidden-type"   value="{{ request('type') }}">

      <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
          <div class="filters-card">
            <div class="sidebar-title d-flex justify-content-between align-items-center">
              <span>{{ __('filters.title') }}</span>
              <a href="{{ route('theme.index') }}" class="btn btn-sm btn-outline-secondary mt-2" data-ajax-link>   {{ __('filters.reset') }}</a>
            </div>
            <div class="hint">{{ __('filters.hint') }}</div>

            {{-- شارة المواهب --}}
            <div class="filter-section">
              <h4 class="filter-subtitle"><i class="bi bi-award"></i> {{ __('filters.badge.title') }}</h4>
              <ul class="filter-list">
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="badge1" name="badges[]" value="top_plus"
                          {{ in_array('top_plus', request('badges', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="badge1"><i class="bi bi-stars"></i> {{ __('filters.badge.top_plus') }}</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="badge2" name="badges[]" value="top"
                          {{ in_array('top', request('badges', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="badge2"><i class="bi bi-trophy"></i> {{ __('filters.badge.top') }}</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="badge3" name="badges[]" value="rising"
                          {{ in_array('rising', request('badges', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="badge3"><i class="bi bi-graph-up-arrow"></i>  {{ __('filters.badge.rising') }}</label>
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
          {{ __('filters.accordion.gender.title') }}
                  </button>
                </h2>
                <div id="collapseGender" class="accordion-collapse collapse" data-bs-parent="#userFilterAccordion">
                  <div class="accordion-body px-0">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gender_male" name="gender[]" value="male"
                            {{ in_array('male', request('gender', [])) ? 'checked' : '' }}>
                      <label class="form-check-label" for="gender_male">{{ __('filters.accordion.gender.male') }}</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gender_female" name="gender[]" value="female"
                            {{ in_array('female', request('gender', [])) ? 'checked' : '' }}>
                      <label class="form-check-label" for="gender_female">{{ __('filters.accordion.gender.female') }}/label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item border-0 filter-section">
                <h2 class="accordion-header" id="headingCountry">
                  <button class="accordion-button collapsed bg-white shadow-none" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapseCountry" aria-controls="collapseCountry">
                    {{ __('filters.accordion.country.title') }}
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
          {{ __('filters.accordion.classification.title') }}
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
              <a href="{{ route('theme.index') }}" class="btn btn-outline-secondary w-100" data-ajax-link>      {{ __('filters.actions.reset') }} </a>
            </div>
          </div>
        </div>

        <!-- Main -->
        <div class="col-lg-9 col-md-8">
          <div class="content-header d-flex justify-content-between align-items-center">
            <div class="results-meta">
@php $total = method_exists($users,'total') ? $users->total() : (is_countable($users) ? count($users) : 0); @endphp
<span id="results-total">{{ __('results.found', ['count' => $total]) }}</span>

            </div>
            <div class="sort-options d-flex align-items-center gap-1 relative">
             <span class="text-muted">{{ __('results.sort_by') }}</span>
<select class="form-select" name="sort" id="sort-select">
  <option value="relevant"  {{ request('sort') == 'relevant' ? 'selected' : '' }}>{{ __('results.sort.relevant') }}</option>
  <option value="newest"    {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('results.sort.newest') }}</option>
  <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>{{ __('results.sort.top_rated') }}</option>
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
@endsection


@push('scripts')
  <style>
    /* لودر بسيط */
    #results-loading{position:relative}
    #results-loading::after{
      content:""; position:absolute; inset:0; background:rgba(255,255,255,.6);
      display:none;
    }
    #results-loading.loading::after{display:block}
    .spinner{
      display:none; margin-inline-start:.5rem; width:16px; height:16px; border-radius:50%;
      border:2px solid currentColor; border-top-color: transparent; animation:spin .7s linear infinite;
    }
    .loading .spinner{display:inline-block}
    @keyframes spin{to{transform:rotate(360deg)}}
  </style>

  <script>
  (function(){
    const baseUrl = "{{ route('theme.index') }}";
    const heroForm     = document.getElementById('heroForm');
    const filterForm   = document.getElementById('filterForm');
    const searchInput  = document.getElementById('search-input');
    const typeSelect   = document.getElementById('hero-type');
    const hiddenSearch = document.getElementById('hidden-search');
    const hiddenType   = document.getElementById('hidden-type');
    const usersBox     = document.getElementById('users-container');
    const pagBox       = document.getElementById('pagination-links');
    const chipsBox     = document.getElementById('active-chips');
    const resultsTotal = document.getElementById('results-total');

    function setLoading(on){
      (usersBox.closest('#results-loading') || usersBox).classList.toggle('loading', !!on);
    }

    function collectQuery(extra = {}){
      const q = new URLSearchParams();

      // من filterForm
      const fd = new FormData(filterForm);
      for (const [k,v] of fd.entries()){
        if (v !== '') q.append(k, v);
      }

      // sync مع الهيرو
      if (searchInput && searchInput.value.trim() !== '') q.set('search', searchInput.value.trim());
      if (typeSelect) q.set('type', typeSelect.value || '');

      // إضافات
      for (const k in extra){
        if (extra[k] === null){ q.delete(k); continue; }
        q.set(k, extra[k]);
      }

      q.set('partial', '1'); // استجابة جزئية
      return q;
    }

    async function fetchAndRender(params, push=false){
      const url = baseUrl + '?' + params.toString();
      setLoading(true);
      try{
        const res = await fetch(url, {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept':'application/json' }
        });
        const data = await res.json();
        if(!data.ok) throw new Error('استجابة غير متوقعة');

        if (data.users_html) usersBox.innerHTML = data.users_html;
        if (data.pagination_html) pagBox.innerHTML = data.pagination_html;
        if (data.chips_html && chipsBox) chipsBox.innerHTML = data.chips_html;
        if (typeof data.total !== 'undefined' && resultsTotal){
          resultsTotal.textContent = `وجدنا ${data.total} نتيجة`;
        }

        if (data.url){
          if (push) history.pushState(null, '', data.url);
          else history.replaceState(null, '', data.url);
        }

        bindAjaxLinks();
      }catch(e){
        console.error(e);
      }finally{
        setLoading(false);
      }
    }

    function debounce(fn, d=300){
      let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), d); };
    }

     function bindAjaxLinks(){
    const pagContainer = document.getElementById('pagination-links');
    if (!pagContainer) return;

    // استخدم delegation، ربط مرّة واحدة يكفي حتى بعد تحديث الـinnerHTML
    pagContainer.addEventListener('click', function(ev){
      const a = ev.target.closest('a');         // أي لينك داخل الباجينيشن
      if(!a) return;

      const href = a.getAttribute('href');
      // تجاهل العناصر غير القابلة للنقر
      if (!href || href === '#' || href.startsWith('javascript:')) return;

      // تعامل مع الروابط من نفس الدومين عبر AJAX
      const u = new URL(href, location.origin);
      if (u.origin !== location.origin) return; // خارجي: خليه يفتح عادي

      ev.preventDefault();

      // خذ رقم الصفحة من الرابط وادمجه مع الكويري الحالي
      const page = u.searchParams.get('page');
      const params = collectQuery(page ? {page} : {});
      fetchAndRender(params, /*push*/ true);
    }, { passive: false });
  }

  // ولو بدك تمسك روابط إزالة الشرائح (chips) بنفس الطريقة (اختياري):
  document.addEventListener('click', function(ev){
    const a = ev.target.closest('a[data-ajax-link]');
    if(!a) return;
    const href = a.getAttribute('href');
    if(!href) return;
    ev.preventDefault();
    const u = new URL(href, location.origin);
    // استخرج page (إن وجد) وارسله
    const page = u.searchParams.get('page');
    const params = collectQuery(page ? {page} : {});
    fetchAndRender(params, /*push*/ true);
  }, { passive:false });

  // نادِ الدالة بعد تعريفها

    function syncHeroToHidden(){
      if (hiddenSearch) hiddenSearch.value = (searchInput?.value ?? '').trim();
      if (hiddenType)   hiddenType.value   = (typeSelect?.value   ?? '');
    }

    function bindFilterInstantSubmit(){
      filterForm.querySelectorAll('input[type="checkbox"], select[name="sort"]').forEach(el=>{
        el.addEventListener('change', ()=>{
          syncHeroToHidden();
          const params = collectQuery();
          fetchAndRender(params, /*push*/ true);
        });
      });
    }

    // إرسال الهيرو
    heroForm?.addEventListener('submit', (e)=>{
      e.preventDefault();
      syncHeroToHidden();
      const params = collectQuery({page:null});
      fetchAndRender(params, /*push*/ true);
      usersBox?.scrollIntoView({behavior:'smooth', block:'start'});
    });

    // كتابة فورية
    if (searchInput){
      searchInput.addEventListener('input', debounce(()=>{
        syncHeroToHidden();
        const params = collectQuery({page:null});
        fetchAndRender(params); // replaceState
      }, 350));
    }

    // تغيير النوع من الهيرو
    typeSelect?.addEventListener('change', ()=>{
      syncHeroToHidden();
      const params = collectQuery({page:null});
      fetchAndRender(params, /*push*/ true);
    });

    // أزرار الاقتراحات
    document.querySelectorAll('.js-fill').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const val = btn.getAttribute('data-value') || '';
        if (searchInput){ searchInput.value = val; }
        syncHeroToHidden();
        const params = collectQuery({page:null});
        fetchAndRender(params, /*push*/ true);
        usersBox?.scrollIntoView({behavior:'smooth', block:'start'});
      });
    });

    // back/forward
    window.addEventListener('popstate', ()=>{
      const now = new URL(location.href);
      const s = now.searchParams.get('search') || '';
      const t = now.searchParams.get('type') || '';
      if (searchInput) searchInput.value = s;
      if (typeSelect)  typeSelect.value  = t;
      syncHeroToHidden();
      now.searchParams.set('partial','1');
      fetchAndRender(now.searchParams, /*push*/ false);
    });

    // التفاف لودر
    (function wrapForLoading(){
      const wrap = document.createElement('div');
      wrap.id = 'results-loading';
      usersBox.parentNode.insertBefore(wrap, usersBox);
      wrap.appendChild(usersBox);
    })();

    bindAjaxLinks();
    bindFilterInstantSubmit();
  })();
  </script>
@endpush
