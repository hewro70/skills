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
          <form id="heroForm" action="{{ route('theme.skills') }}" method="GET" class="hero-search" role="search" autocomplete="off" aria-label="{{ __('home.hero.search.aria') }}">
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

            {{--  <div class="select-wrap">
              @php $currType = request('type'); @endphp
              <select name="type" class="form-select" aria-label="{{ __('home.hero.select.aria') }}" id="hero-type">
    <option value="" {{ $currType==='' || $currType===null ? 'selected' : '' }}>{{ __('home.hero.select.all') }}</option>
    <option value="language" {{ $currType==='language' ? 'selected' : '' }}>{{ __('home.hero.select.language') }}</option>
    <option value="tech"     {{ $currType==='tech' ? 'selected' : '' }}>{{ __('home.hero.select.tech') }}</option>
    <option value="music"    {{ $currType==='music' ? 'selected' : '' }}>{{ __('home.hero.select.music') }}</option>
    <option value="art"      {{ $currType==='art' ? 'selected' : '' }}>{{ __('home.hero.select.art') }}</option>
    <option value="academic" {{ $currType==='academic' ? 'selected' : '' }}>{{ __('home.hero.select.academic') }}</option>
  </select>
            </div>  --}}

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


        </div>
      </div>
    </div>
  </section>
{{-- ================== WHY SKILLS HUB ================== --}}
<section id="why-skills-hub" class="why-skills-hub section py-5">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>{{ __('home.why.title') }}</h2>
      <p>{{ __('home.why.subtitle') }}</p>
    </div>

    @php $whyItems = __('home.why.items'); @endphp
    <div class="row gy-4">
      @foreach($whyItems as $item)
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
          <div class="feature-box h-100 p-3 rounded-3 border">
            <div class="icon-box bg-primary text-white mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;">
              <i class="bi {{ $item['icon'] }}"></i>
            </div>
            <h3 class="h5">{{ $item['title'] }}</h3>
            <p class="mb-0">{{ $item['text'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ================== HOW IT WORKS ================== --}}
<section id="how-it-works" class="how-it-works section py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>{{ __('home.how.title') }}</h2>
      <p>{{ __('home.how.subtitle') }}</p>
    </div>

    @php $steps = __('home.how.steps'); @endphp
    <div class="row gy-4 justify-content-center" id="index-row">
      @foreach($steps as $step)
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
          <div class="step-box text-center h-100 p-4 rounded-3 border bg-white">
            <div class="step-number bg-primary text-white mb-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
              {{ $step['num'] }}
            </div>
            <h3 class="h5">{{ $step['title'] }}</h3>
            <p class="mb-0">{{ $step['text'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ================== COMMUNITY ================== --}}
<section id="community" class="community section py-5">
  <div class="container">
    <div class="row align-items-center" id="row-community-id">
      <div class="col-lg-6 order-lg-1 mb-4 mb-lg-0" data-aos="fade-right">
        <img src="{{ __('home.community.image') }}" id="img-fluid" class="img-fluid rounded" alt="{{ __('home.community.alt') }}">
      </div>
      <div class="col-lg-6 order-lg-2" data-aos="fade-left">
        <div class="pe-lg-5">
          <h2 class="mb-3">{{ __('home.community.title') }}</h2>
          <p class="lead mb-4">{{ __('home.community.lead') }}</p>

          @php $features = __('home.community.features'); @endphp
          <div class="community-features">
            @foreach($features as $f)
              <div class="feature-item d-flex mb-3">
                <div class="icon-box bg-primary text-white me-3 flex-shrink-0 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                  <i class="bi bi-check2-circle"></i>
                </div>
                <div><p class="mb-0 feature-text">{{ $f }}</p></div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ================== FAQ ================== --}}
<section id="faq" class="faq section py-5">
  <div class="container" data-aos="fade-up">
    <div class="section-title text-center mb-5">
      <h2 id="faq-header">{{ __('home.faq.title') }}</h2>
      <p>{{ __('home.faq.subtitle') }}</p>
    </div>

    @php $faq = __('home.faq.items'); @endphp
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion" id="faqAccordion">
          @foreach($faq as $i => $qa)
            @php $collapseId = "faq".($i+1); @endphp
            <div class="accordion-item">
              <h3 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#{{ $collapseId }}" style="color:#37423b !important">
                  {{ $qa['q'] }}
                </button>
              </h3>
              <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">{{ $qa['a'] }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>
</section>


@endsection
@push('styles')
<style>
  /* ثبّت محاذاة الزر والأيقونة */
  #faq .accordion-button{
    display:flex; align-items:center;
    gap:.75rem;
    /* حشوات منطقية (تشتغل RTL/LTR) */
    padding-inline-start: 1rem;
    padding-inline-end: 3rem;
    text-align: start; /* يمنع تمركز النص */
  }
  /* الأيقونة الافتراضية لبووتستراب */
  #faq .accordion-button::after{
    flex-shrink:0;            /* لا تتمدد مع النص */
    width: 1rem; height: 1rem;
    background-size: 1rem 1rem;
    margin-inline-start: auto;/* ادفع الأيقونة لنهاية السطر */
    margin-inline-end: 0;
  }
  /* في RTL بدّل الحشوات بحيث تبقى الأيقونة بنهاية السطر (اليسار) */
  [dir="rtl"] #faq .accordion-button{
    padding-inline-start: 3rem;
    padding-inline-end: 1rem;
  }
  [dir="rtl"] #faq .accordion-button::after{
    margin-inline-start: 0;
    margin-inline-end: auto;
  }

  /* تحسين شكل العناصر شوي */
  #faq .accordion-item{
    border: 1px solid #e5e7eb;
    border-radius: .75rem;
    overflow: hidden;
    margin-bottom: .75rem;
  }
  #faq .accordion-button:not(.collapsed){
    background: #fbfbfd;
    color:#0d1b2a;
    box-shadow: none;
  }
</style>
@endpush

@push('scripts')
  <style>
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
  const heroForm   = document.getElementById('heroForm');
  const searchInput= document.getElementById('search-input');
  const typeSelect = document.getElementById('hero-type');

  // ✅ سبميت = انتقال مباشر لصفحة السكيلز
  heroForm?.addEventListener('submit', (e)=>{
    e.preventDefault();
    const to = new URL("{{ route('theme.skills') }}", location.origin);
    const s  = (searchInput?.value || '').trim();
    const t  = (typeSelect?.value   || '').trim();
    if (s) to.searchParams.set('search', s);
    if (t) to.searchParams.set('type',   t);
    to.searchParams.delete('page');
    window.location.href = to.toString();
  });

  // ✅ الاقتراحات السريعة تروح مباشرة لسكيلز
  document.querySelectorAll('.js-fill').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const val = btn.getAttribute('data-value') || '';
      const to  = new URL("{{ route('theme.skills') }}", location.origin);
      if (val) to.searchParams.set('search', val);
      window.location.href = to.toString();
    });
  });
})();

  </script>
@endpush
