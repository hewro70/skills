]) 

@php
  use Illuminate\Support\Str;

  // ====== خيارات قابلة للتمرير من include ======
  $title        = $title        ?? __('hero.title', [], app()->getLocale())        ?? __('app.title');
  $description  = $description  ?? __('hero.description', [], app()->getLocale())  ?? '';
  $badge        = $badge        ?? __('hero.badge', [], app()->getLocale())        ?? null;
  $current      = $current      ?? null;
  $homeLabel    = $homeLabel    ?? __('nav.home', [], app()->getLocale())          ?? 'Home';

  // صورة خلفية اختيارية
  $bgImage      = $bgImage      ?? null;

  // أزرار اختيارية
  // مثال: ['label' => 'ابدأ الآن', 'href' => route('register')]
  $primaryBtn   = $primaryBtn   ?? null;
  // مثال: ['label' => 'تعلّم المزيد', 'href' => route('theme.about')]
  $secondaryBtn = $secondaryBtn ?? null;

  // ارتفاع: sm / md / lg
  $height  = in_array(($height ?? 'md'), ['sm','md','lg']) ? $height : 'md';

  // Light / Dark / Auto overlay
  $overlay = in_array(($overlay ?? 'auto'), ['light','dark','auto']) ? $overlay : 'auto';

  // RTL / LTR
  $isRtl = app()->isLocale('ar');
  $dir   = $isRtl ? 'rtl' : 'ltr';

  // تدرّج افتراضي (يُستخدم كخلفية بدل الصورة)
  $gradient = $gradient ?? 'linear-gradient(135deg, #7ec8e3 0%, #a0d8ef 40%, #c7eaf5 100%)';

  // نصوص افتراضية
  if (!$title)       $title = $current ?? $homeLabel;
  if (!$description) $description = __('hero.fallback', [], app()->getLocale()) ?? '';

  // --- تطبيع رابط الخلفية ---
  $bg = $bgImage;
  if ($bg) {
    if (!Str::startsWith($bg, ['http://', 'https://', '/'])) {
      $bg = asset($bg);
    }
    if (request()->isSecure() && Str::startsWith($bg, 'http://')) {
      $bg = preg_replace('#^http://#', 'https://', $bg);
    }
  }

  // 🔒 هذا السطر يعطّل الصورة نهائيًا (يخلّي الخلفية تدرّج فقط)
  $bg = null;

  // طبقة التعتيم
  $overlayClass = match ($overlay) {
    'light' => 'mh-hero__overlay--light',
    'dark'  => 'mh-hero__overlay--dark',
    default => 'mh-hero__overlay--none',
  };
@endphp

<div class="mh-hero {{ 'mh-hero--'.$height }} {{ $isRtl ? 'is-rtl' : 'is-ltr' }}" dir="{{ $dir }}" aria-label="page intro">
  {{-- الخلفية: تدرّج فقط حالياً --}}
  <div class="mh-hero__bg" style="background-image: {{ $gradient }};"></div>

  {{-- طبقة تعتيم --}}
  <div class="mh-hero__overlay {{ $overlayClass }}"></div>

  {{-- شكل زخرفي خفيف --}}
  <div class="mh-hero__shape"></div>

  <div class="container position-relative mh-hero__container">
    @if($badge)
      <span class="mh-hero__badge">{{ $badge }}</span>
    @endif

    <h1 class="mh-hero__title">{{ $title }}</h1>

    @if($description)
      <p class="mh-hero__desc">{{ $description }}</p>
    @endif

    @if($primaryBtn || $secondaryBtn)
      <div class="mh-hero__actions">
        @isset($primaryBtn)
          <a href="{{ $primaryBtn['href'] ?? '#' }}" class="mh-btn mh-btn--primary">
            {{ $primaryBtn['label'] ?? '' }}
          </a>
        @endisset

        @isset($secondaryBtn)
          <a href="{{ $secondaryBtn['href'] ?? '#' }}" class="mh-btn mh-btn--ghost">
            {{ $secondaryBtn['label'] ?? '' }}
          </a>
        @endisset
      </div>
    @endif
  </div>

  {{-- فتات الخبز --}}
  <nav class="mh-breadcrumbs" aria-label="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('theme.index') }}">{{ $homeLabel }}</a></li>
        @if($current)
          <li class="current">{{ $current }}</li>
        @else
          <li class="current">{{ $title }}</li>
        @endif
      </ol>
    </div>
  </nav>
</div>

{{-- ============== STYLES ============== --}}
<style>
  :root{
    --mh-hero-pad-sm: clamp(32px, 7vw, 56px);
    --mh-hero-pad-md: clamp(48px, 9vw, 80px);
    --mh-hero-pad-lg: clamp(72px, 12vw, 120px);
    --mh-ink: #0f172a; --mh-muted: #64748b; --mh-white: #fff; --mh-brand: #000;
  }

  .mh-hero{ position:relative; margin:0; padding:0; background:transparent; overflow:hidden; }
  .mh-hero__bg{
    position:absolute; inset:0;
    background-size:cover; background-position:center;
    transition: transform .6s ease;
    z-index:0;
  }
  .mh-hero:hover .mh-hero__bg{ transform: scale(1.01); }

  .mh-hero__overlay{ position:absolute; inset:0; pointer-events:none; transition: opacity .4s ease; z-index:1; }
  .mh-hero__overlay--none{ background: transparent; }
  .mh-hero__overlay--light{ background: linear-gradient(180deg, rgba(255,255,255,.28), rgba(255,255,255,.08)); }
  .mh-hero__overlay--dark{  background: linear-gradient(180deg, rgba(0,0,0,.22), rgba(0,0,0,.32)); }

  .mh-hero__shape{
    position:absolute; inset-inline: -10%; top: -60px; height: 180px;
    background: radial-gradient(closest-side, rgba(255,255,255,.18), rgba(255,255,255,0));
    filter: blur(12px); z-index:1;
  }

  .mh-hero__container{
    position:relative; z-index:2; text-align:center; color:var(--mh-white);
    padding-block: var(--mh-hero-pad-md);
  }
  .mh-hero--sm .mh-hero__container{ padding-block: var(--mh-hero-pad-sm); }
  .mh-hero--lg .mh-hero__container{ padding-block: var(--mh-hero-pad-lg); }

  .mh-hero__badge{
    display:inline-block; background:#fff; color:#111; font-weight:700;
    padding:.35rem .75rem; border-radius:999px; margin-bottom:12px;
    box-shadow: 0 6px 18px rgba(2,6,23,.08);
  }

  .mh-hero__title{
    color:#fff; font-weight:800; letter-spacing:.2px;
    font-size: clamp(1.6rem, 3.6vw, 2.6rem);
    margin: 0 0 8px 0; text-shadow: 0 2px 6px rgba(0,0,0,.12);
  }

  .mh-hero__desc{
    margin:0 auto; max-width: 760px; line-height:1.7;
    font-size: clamp(.98rem, 1.4vw, 1.1rem); color: rgba(255,255,255,.95);
  }

  .mh-hero__actions{ margin-top:16px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
  .mh-btn{
    display:inline-flex; align-items:center; justify-content:center;
    padding:.6rem 1.1rem; border-radius:999px; font-weight:700; text-decoration:none; transition: all .22s ease;
  }
  .mh-btn--primary{ background:#000; color:#fff; box-shadow:0 8px 20px rgba(0,0,0,.12); }
  .mh-btn--primary:hover{ transform: translateY(-1px); background:#111; }
  .mh-btn--ghost{
    background: rgba(255,255,255,.12); color:#fff; border:1px solid rgba(255,255,255,.35); backdrop-filter: blur(6px);
  }
  .mh-btn--ghost:hover{ background: rgba(255,255,255,.18); transform: translateY(-1px); }

  /* ===== Breadcrumbs ===== */
  .mh-breadcrumbs{
    background:#fff; border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9;
    box-shadow: 0 2px 8px rgba(0,0,0,.02);
  }
  .mh-breadcrumbs .container{ padding-block: 12px; }
  .mh-breadcrumbs ol{
    margin:0; padding:0; list-style:none; display:flex; gap:6px; align-items:center; justify-content:center; flex-wrap:wrap;
  }
  .mh-breadcrumbs li{ font-size:.95rem; color:#64748b; font-weight:600; display:flex; align-items:center; }
  .mh-breadcrumbs li:not(:last-child)::after{
    content:"›"; margin:0 6px; color:#cbd5e1; font-size:1rem; font-weight:700;
  }
  .mh-breadcrumbs li a{ color:#334155; text-decoration:none; padding:3px 6px; border-radius:6px; }
  .mh-breadcrumbs li a:hover{ background:#f3f4f6; }
  .mh-breadcrumbs .current{
    color:#2563eb; background:rgba(37,99,235,.08); padding:3px 10px; border-radius:8px; font-weight:800;
  }
  .is-rtl .mh-breadcrumbs li:not(:last-child)::after{ transform: scaleX(-1); }
</style>
