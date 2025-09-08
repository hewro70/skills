<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maharat Hub</title>

<meta name="description" content="Connect with people globally to share knowledge, learn new skills, and exchange expertise for free.">

<meta name="keywords" content="Maharat Hub, skill exchange, share knowledge, online learning, free learning, community learning, learn skills, knowledge exchange, connect with people, global learning">

<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

{{-- Open Graph for social & SEO --}}
@php
  $TITLE = 'Maharat Hub';
  $DESC  = 'Connect with people globally to share knowledge, learn new skills, and exchange expertise for free.';
@endphp

<title>{{ $TITLE }}</title>
<meta name="description" content="{{ $DESC }}">
<meta name="keywords" content="Maharat Hub, skill exchange, share knowledge, online learning, free learning, community learning">

<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $TITLE }}">
<meta property="og:description" content="{{ $DESC }}">
<meta property="og:image" content="{{ asset('img/logo.png') }}">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="en_US">

<!-- Robots (يسمح بأطول سنِّبت ممكن) -->
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">



  <!-- Bootswatch (Lux) -->
<!-- Bootstrap الأساسي -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- بعده ثيم Bootswatch -->
<link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- أساسيات ستايل -->
  <style>
    :root{ --transition:all .3s ease; --bs-primary:#000; --bs-primary-rgb:0,0,0; }
    body{ font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .btn-primary{
      background-color:var(--bs-primary)!important; border-color:var(--bs-primary)!important;
      border-radius:999px; padding:.5rem 1.25rem; font-weight:600; transition:var(--transition);
    }
    .btn-primary:hover,.btn-primary:focus{
      background-color:#111!important; border-color:#111!important;
      transform:translateY(-1px); box-shadow:0 .5rem 1rem rgba(0,0,0,.12);
    }

    .header{
      transition:var(--transition);
      box-shadow:0 2px 15px rgba(0,0,0,.06);
      backdrop-filter:blur(10px);
      background-color:rgba(255,255,255,.96)!important;
    }
    .navbar.fixed-top,.header.sticky-top{ z-index:1100; }

    .logo{ font-weight:800; font-size:1.35rem; color:#111; text-decoration:none; transition:var(--transition); }
    .logo:hover{ transform:scale(1.03); opacity:.9; }
    .logo img{ width:55px; height:40px; object-fit:cover; transition:var(--transition); }
    .logo:hover img{ transform:rotate(4deg); }

    /* Desktop nav */
    .desktop-nav .nav-link{
      position:relative; font-weight:600; color:#2b2d42;
      padding:.5rem .9rem!important; border-radius:.6rem; transition:var(--transition);
      margin:0 2px;
    }
    .desktop-nav .nav-link:hover{ color:#000; background-color:transparent; transform:rotate(4deg); }

    .desktop-nav .nav-link:hover::after{ width:80%; opacity:.8; transform:rotate(4deg); }
    header .desktop-nav .nav-link.active{
      color:#fff!important; background:#000!important; border-radius:999px; box-shadow:0 6px 18px rgba(0,0,0,.08);
    }
    header .desktop-nav .nav-link.active::after{ width:0!important; }

    /* Dropdown */
    .dropdown-menu{
      border:none; border-radius:.8rem;
      box-shadow:0 10px 30px rgba(0,0,0,.08);
      padding:.5rem;
    }
    .dropdown-item{
      border-radius:.5rem; padding:.6rem .9rem; font-weight:500; transition:var(--transition);
    }
    .dropdown-item:hover{ background:rgba(0,0,0,.06); color:#111; }

    /* زر القائمة للموبايل */
    #mobileNavToggle{
      border:none; border-radius:.6rem; padding:.55rem .7rem;
      background:#f5f6f8; color:#111; transition:var(--transition);
    }
    #mobileNavToggle:hover{ background:rgba(0,0,0,.08); }

    /* Mobile nav (انزلاق ناعم) */
    #navmenu{ overflow:hidden; transition:max-height .35s ease, padding .35s ease; max-height:0; }
    #navmenu.show{ max-height:420px; padding:.8rem 0; }
    .nav-open #navmenu{ max-height:420px; padding:.8rem 0; }

    #navmenu .nav-link{ border-radius:.6rem; }
    #navmenu .nav-link.active{
      background:#000!important; color:#fff!important; border-radius:999px;
    }

    /* رسبونسف */
    @media (max-width:1199.98px){ .desktop-nav{ display:none; } }
    @media (min-width:1200px){ #mobileNavToggle{ display:none; } #navmenu{ display:none; } }
    @media (max-width:575.98px){
      .logo span{ font-size:1.05rem; }
      .auth-section .btn{ padding:.4rem 1rem; font-size:.85rem; }
    }

    /* بادجات العدادات بجانب النص */
    .pill-link{
      display:inline-flex; align-items:center; gap:.5rem;
      background:#f5f6f8; border:1px solid #e5e7eb; border-radius:999px;
      padding:.35rem .75rem; text-decoration:none; color:#111; font-weight:600;
      transition:var(--transition);
    }
    .pill-link:hover{ background:#111; color:#fff; transform:translateY(-1px); }
    .pill-badge{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:1.35rem; height:1.35rem; border-radius:999px;
      background:#f59e0b; color:#fff; font-size:.75rem; padding:0 .35rem;
      border:2px solid #fff;
    }

    /* محاذاة قائمة إشعارات الملف الشخصي */
    [dir="rtl"] .dropdown-menu-end{ --bs-position: end; }
    .premium-pill{
  display:inline-flex; align-items:center; gap:.35rem;
  background:linear-gradient(90deg,#f59e0b,#fbbf24);
  color:#111; border:0; padding:.4rem .9rem; border-radius:999px;
  font-weight:700; box-shadow:0 6px 16px rgba(245,158,11,.25);
  transition:all .2s ease; cursor:pointer;
}
.premium-pill:hover{ transform:translateY(-1px); filter:brightness(.98); }
.premium-pill .bi-crown{ color:#111; }

  </style>
  <style>
/* زر الجرس: مربع صغير مع تموضع نسبي */
.notif-btn{
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 38px; height: 38px;
  line-height: 1;
  padding: 0;
  background: transparent;
  border: 0;
}

/* البادج: نفس أبعاد pill-badge تقريبًا، وباستخدام منطق RTL/LTR */
.notif-badge{
  position: absolute;
  inset-block-start: 0;            /* top: 0 (يدعم RTL/LTR) */
  inset-inline-start: 100%;        /* عند نهاية الجهة البادئة (يمين في RTL/يسار في LTR) */
  transform: translate(-50%, -40%);/* ازاحة خفيفة لأعلى ولداخل */
  min-width: 1.35rem;
  height: 1.35rem;
  padding: 0 .35rem;
  border-radius: 999px;
  background: #dc3545;             /* bg-danger */
  color: #fff;
  font-size: .75rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #fff;          /* نفس ستايل العدادات الأخرى */
  box-sizing: border-box;
  font-weight: 600;
}
.notif-badge{ z-index: 5; }

/* في RTL نعكس اتجاه الإزاحة الأفقية */
[dir="rtl"] .notif-badge{
  transform: translate(50%, -40%);
}
/* 1) بادنج داخلي للقائمة + مظهر ألطف للعناصر */
.notif-menu { padding:.5rem; }
#notifList { padding:.25rem; } /* مسافة حول العناصر */
#notifList .item{
  display:flex; gap:.6rem; align-items:flex-start;
  padding:.6rem .75rem; border-radius:.5rem;
}
#notifList .item:hover{ background:#f8f9fa; }
#notifList .title{ font-weight:600; }
#notifList .meta{ font-size:.8rem; color:#6c757d; }

/* 2) البادج: نزّلها لتحت وثبّت المحاذاة لوجيكياً (RTL/LTR) */
.notif-btn{ position:relative; display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; padding:0; background:transparent; border:0; line-height:1; }
.notif-badge{
  position:absolute;
  inset-block-start: 6px;          /* ← نزّل البادج شوي */
  inset-inline-start: 100%;        /* عند نهاية الجهة (يمين RTL/يسار LTR) */
  transform: translate(-55%, 0);   /* إدخال خفيف للداخل */
  min-width:1.35rem; height:1.35rem; padding:0 .35rem; border-radius:999px;
  display:inline-flex; align-items:center; justify-content:center;
  font-size:.75rem; font-weight:600; box-sizing:border-box; z-index:5;
  border:2px solid #fff;
  background:#f59e0b;              /* 3) نفس لون أخواته */
  color:#fff;
}
[dir="rtl"] .notif-badge{ transform: translate(55%, 0); } /* عكس الإزاحة في RTL */


</style>


  @auth
  <meta name="user-id" content="{{ auth()->id() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}">
  <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') ?? 'mt1' }}">
  @endauth
@php
  $u = auth()->user();
  // عدّل الشرط حسب نظامك (حقل is_premium أو دالة اشتراك)
  $isPremium = auth()->check() && (($u->is_premium ?? false) || (method_exists($u,'hasActiveSubscription') && $u->hasActiveSubscription()));
@endphp
@auth
  @unless($isPremium)
    @include('theme.conversations.modals.premium', [
      'modalId' => 'premiumModal',
      'subscribeModalId' => 'subscribeModal',
    ])

    @include('theme.conversations.modals.subscribe', [
      'modalId' => 'subscribeModal',
      'formId'  => 'subscribeForm',
      // إعداداتك الحالية لو بدك
      'config'  => [
        'price'        => '5 USD',
        'wallet_email' => 'wallet@example.com',
        'wallet_name'  => 'Premium Wallet',
        'paypal'       => 'payments@yourapp.com',
      ],
      'prefill' => [
        'email'        => auth()->user()->email ?? '',
        'account_name' => auth()->user()->name ?? 'your_account',
      ],
    ])
  @endunless
@endauth

  <!-- Pusher -->
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <!-- Bootstrap JS (مهم لتشغيل الـ Dropdown) -->
</head>
<body>
 
<header id="header" class="header sticky-top bg-white border-bottom">
  <div class="container-fluid container-xl">
    <div class="headerbar d-flex align-items-center justify-content-between py-2">
      {{-- يسار: حساب/تسجيل --}}
      <div class="auth-section d-flex align-items-center gap-3 order-1">
        @auth
          <div class="dropdown">
            <button class="btn btn-link p-0 d-flex align-items-center text-decoration-none" id="dropdownUser"
                    data-bs-toggle="dropdown" aria-expanded="false">
              @php
                $u        = auth()->user();
                $raw      = $u?->image_url;
                $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($u?->fullName() ?: $u?->name ?: $u?->email);
                if (!empty($raw)) {
                    if (preg_match('/^https?:\/\//i', $raw)) { $src = $raw; }
                    else {
                      $path = ltrim($raw, '/');
                      $src  = (strpos($path, 'storage/') === 0) ? asset($path) : asset('storage/' . $path);
                    }
                } else { $src = $fallback; }
                $src .= (str_contains($src, '?') ? '&' : '?') . 'v=' . ((optional($u?->updated_at)->timestamp) ?? time());
              @endphp
              <img src="{{ $src }}" alt="avatar" class="rounded-circle border user-avatar"
                   style="width:38px;height:38px;object-fit:cover;"
                   onerror="this.onerror=null; this.src='{{ $fallback }}';"/>
              <span class="d-none d-md-inline fw-semibold text-dark text-truncate mx-2" style="max-width:140px;">
                {{ auth()->user()->fullName() ?: auth()->user()->email }}
              </span>
              <i class="bi bi-chevron-down small text-muted d-none d-md-inline"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2" aria-labelledby="dropdownUser">
              <li><a class="dropdown-item d-flex align-items-center" href="{{ route('myProfile') }}">
                <i class="fas fa-user me-2 text-primary"></i> {{ __('auth.my_account') }}</a></li>
              <li><a class="dropdown-item d-flex align-items-center" href="{{ route('invitations.index') }}">
                <i class="fas fa-envelope-open-text me-2 text-warning"></i> {{ __('auth.invitations') }}</a></li>
              <li><a class="dropdown-item d-flex align-items-center" href="{{ route('conversations.index') }}">
                <i class="fas fa-comments me-2 text-success"></i> {{ __('auth.conversations') }}</a></li>
              <li><hr class="dropdown-divider my-2"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">@csrf
                  <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('auth.logout') }}
                  </button>
                </form>
              </li>
            </ul>
          </div>

   
    {{-- Bell + Dropdown --}}
{{-- Bell + Dropdown --}}
<div class="dropdown" id="notifDropdownWrap">
  <button class="notif-btn" id="notifBellBtn"
          data-bs-toggle="dropdown" aria-expanded="false"
          aria-label="@if(app()->isLocale('ar')) الإشعارات @else Notifications @endif">
    <i class="bi bi-bell fs-5"></i>
    <span id="notif-badge" class="notif-badge d-none">0</span>
  </button>

  <div class="dropdown-menu dropdown-menu-end shadow-sm p-0 notif-menu"
       aria-labelledby="notifBellBtn" style="min-width:320px">
    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
      <strong>@if(app()->isLocale('ar')) الإشعارات @else Notifications @endif</strong>
      <button type="button" class="btn btn-sm btn-link" id="notifRefreshBtn">
        @if(app()->isLocale('ar')) تحديث @else Refresh @endif
      </button>
    </div>

    <div id="notifList" style="max-height:360px; overflow:auto;">
      <div class="p-3 text-muted small" id="notifEmpty">
        @if(app()->isLocale('ar')) لا توجد إشعارات جديدة @else No new notifications @endif
      </div>
    </div>

    <div class="border-top px-3 py-2 small text-center">
      <a href="{{ route('conversations.index') }}" class="text-decoration-none me-2">
        <i class="bi bi-chat-dots me-1"></i>@if(app()->isLocale('ar')) الرسائل @else Messages @endif
      </a>
      <a href="{{ route('invitations.index') }}" class="text-decoration-none">
        <i class="bi bi-envelope-open me-1"></i>@if(app()->isLocale('ar')) الدعوات @else Invitations @endif
      </a>
    </div>
  </div>
</div>

          
<nav class="d-none d-md-flex align-items-center gap-2">
  <a href="{{ route('conversations.index') }}" class="pill-link">
    <i class="bi bi-chat-left-text icon-gap"></i>
    {{ __('auth.messages') ?? 'الرسائل' }}
    <span id="count-chats" class="pill-badge" style="display:none">0</span>
  </a>

  <a href="{{ route('invitations.index') }}" class="pill-link">
    <i class="bi bi-envelope-open icon-gap"></i>
    {{ __('auth.invitations') ?? 'الدعوات' }}
    <span id="count-invitations" class="pill-badge" style="display:none">0</span>
  </a>

  {{--  <a href="{{ route('exchanges.index') }}" class="pill-link">
    <i class="bi bi-arrow-left-right icon-gap"></i>
    {{ __('auth.exchanges') ?? 'التبادلات' }}
    <span id="count-exchanges" class="pill-badge" style="display:none">0</span>
  </a>  --}}

  @if(!$isPremium)
    <button class="premium-pill ms-1"
            data-bs-toggle="modal"
            data-bs-target="#premiumModal"
            type="button">
      <i class="bi bi-crown me-1"></i> {{ __('premium.go_premium') ?? 'Get Premium' }}
    </button>
  @endif
          </nav>
        @else
          <a class="btn btn-primary px-3 fw-semibold shadow-sm" href="{{ route('login') }}">
            {{ __('auth.login_or_register') }}
          </a>
        @endauth
      </div>

      {{-- وسط: الشعار --}}
      <a href="{{ route('theme.index') }}" class="logo d-flex align-items-center order-4 text-decoration-none">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
      </a>

      {{-- يمين: روابط دسكتوب + اللغة + زر الموبايل --}}
      <div class="d-flex align-items-center gap-3 order-3">
        <div class="desktop-nav d-none d-xl-flex">
          <ul class="navbar-nav flex-row gap-1">
            <!--<li class="nav-item">-->
            <!--  <a href="{{ route('theme.index') }}" class="nav-link @yield('index-active')">{{ __('nav.home') }}</a>-->
            <!--</li>-->
                        <li class="nav-item"><a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">{{ __('nav.skills') }}</a></li>

            @guest
              <li class="nav-item"><a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">{{ __('nav.about') }}</a></li>
              <li class="nav-item"><a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">{{ __('nav.contact') }}</a></li>
            @endguest
          </ul>
        </div>

        @php
          $urlAr = route('lang.switch','ar');
          $urlEn = route('lang.switch','en');
        @endphp
        <select id="lang-select" class="form-select form-select-sm fw-semibold d-none d-sm-block"
                style="width:auto"
                data-url-ar="{{ $urlAr }}"
                data-url-en="{{ $urlEn }}">
          <option value="ar" @selected(app()->isLocale('ar'))>{{ __('lang.ar') }}</option>
          <option value="en" @selected(app()->isLocale('en'))>{{ __('lang.en') }}</option>
        </select>

        <button class="btn btn-outline-secondary d-xl-none" id="mobileNavToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navmenu">
          <i class="bi bi-list"></i>
        </button>
      </div>
    </div>
  </div>

  {{-- Mobile Nav --}}
  <nav id="navmenu" class="border-top bg-white">
    <div class="container-xl py-2">
      <ul class="navbar-nav">
        <!--<li class="nav-item">-->
        <!--  <a href="{{ route('theme.index') }}" class="nav-link @yield('index-active')">-->
        <!--    <i class="fas fa-home me-2"></i> {{ __('nav.home') }}-->
        <!--  </a>-->
        <!--</li>-->
        <li class="nav-item"><a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">{{ __('nav.skills') }}</a></li>
          @guest
              <li class="nav-item"><a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">{{ __('nav.about') }}</a></li>
              <li class="nav-item"><a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">{{ __('nav.contact') }}</a></li>
            @endguest
        @auth
        <li><hr class="dropdown-divider my-2"></li>
        <!-- نفس الروابط النصية في الموبايل مع بادجات -->
        <li class="nav-item d-flex align-items-center justify-content-between">
          <a href="{{ route('conversations.index') }}" class="nav-link">{{ __('auth.messages') ?? 'الرسائل' }}</a>
          <span id="m-count-chats" class="badge text-bg-warning" style="display:none">0</span>
        </li>
        <li class="nav-item d-flex align-items-center justify-content-between">
          <a href="{{ route('invitations.index') }}" class="nav-link">{{ __('auth.invitations') ?? 'الدعوات' }}</a>
          <span id="m-count-invitations" class="badge text-bg-warning" style="display:none">0</span>
        </li>
        @if(!$isPremium)
    <li class="nav-item mt-1">
      <button class="btn btn-warning w-100 rounded-pill fw-semibold"
              data-bs-toggle="modal"
              data-bs-target="#premiumModal"
              type="button">
        <i class="bi bi-crown me-1"></i> {{ __('premium.go_premium') ?? 'Get Premium' }}
      </button>
    </li>
  @endif
        {{--  <li class="nav-item d-flex align-items-center justify-content-between">
          <a href="{{ route('invitations.index') }}#exchanges" class="nav-link">{{ __('auth.exchanges') ?? 'التبادلات' }}</a>
          <span id="m-count-exchanges" class="badge text-bg-warning" style="display:none">0</span>
        </li>  --}}
        @endauth
      </ul>
    </div>
  </nav>
</header>

<!-- لغة -->
<script>
(function(){
  const sel = document.getElementById('lang-select');
  if (!sel) return;
  const urlAr = sel.getAttribute('data-url-ar');
  const urlEn = sel.getAttribute('data-url-en');
  sel.addEventListener('change', function(){
    const val = this.value === 'ar' ? 'ar' : 'en';
    const fallback = '/lang/' + val;
    const to = (val === 'ar' ? urlAr : urlEn) || fallback;
    window.location.href = to;
  });
})();
</script>

<!-- فتح/إغلاق قائمة الموبايل -->
<script>
(function(){
  const btn = document.getElementById('mobileNavToggle');
  const nav = document.getElementById('navmenu');
  if(!btn || !nav) return;
  btn.addEventListener('click', function(){
    const open = nav.classList.toggle('show');
    document.body.classList.toggle('nav-open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
})();
</script>

<!-- عدادات الرسائل/الدعوات (Pusher + API) -->
<script>
(function(){
  "use strict";
  const meta     = (n)=>document.querySelector(`meta[name="${n}"]`)?.content;
  const userId   = meta('user-id');     // ← موجود أصلًا
  if (!userId) return;                  // ← أضِف هذا السطر "هنا بالضبط"

  const key      = meta('pusher-key');
  const cluster  = meta('pusher-cluster') || 'mt1';
  const csrf     = meta('csrf-token');

  // دوال اظهار/اخفاء البادجات
  function show(el, n){
    if (!el) return;
    el.textContent = n;
    el.style.display = (Number(n) > 0) ? '' : 'none';
  }

  // عناصر الدسكتوب
  const dChats = document.getElementById('count-chats');
  const dInvs  = document.getElementById('count-invitations');
  const dExch  = document.getElementById('count-exchanges');
  // عناصر الموبايل
  const mChats = document.getElementById('m-count-chats');
  const mInvs  = document.getElementById('m-count-invitations');
  const mExch  = document.getElementById('m-count-exchanges');

  const counts = { chats:0, invitations:0, exchanges:0 };
  const render = ()=>{ show(dChats,counts.chats); show(dInvs,counts.invitations); show(dExch,counts.exchanges);
                       show(mChats,counts.chats); show(mInvs,counts.invitations); show(mExch,counts.exchanges); };

  // جلب الأرقام أول مرة
  @if (Route::has('notifications.counts'))
  fetch('{{ route('notifications.counts') }}')
    .then(r => r.json())
    .then(d => {
      counts.chats       = Number(d.chats||0);
      counts.invitations = Number(d.invitations||0);
      counts.exchanges   = Number(d.exchanges||0);
      render();
    })
    .catch(()=>{});
  @endif

  // بث مباشر
  if (userId && key) {
    const pusher = new Pusher(key, {
      cluster, forceTLS:true,
      authEndpoint: '/broadcasting/auth',
      auth: { headers: { 'X-CSRF-TOKEN': csrf } }
    });
    const channel = pusher.subscribe('private-App.Models.User.' + userId);
    channel.bind('invitation.sent', () => { counts.invitations++; render(); });
    channel.bind('message.sent',    () => { counts.chats++;       render(); });
    channel.bind('exchange.updated',(e)=> {
      if (e?.action === 'created') counts.exchanges++;
      render();
    });
  }

  window.refreshNotifCounts = function(){
    if (!userId) return;              
    fetch('/notifications/counts')
      .then(r=>r.json())
      .then(d=>{ /* ... */ })
      .catch(()=>{});
  };
})();
</script>
<script>
(function(){
  "use strict";
  const meta  = n => document.querySelector(`meta[name="${n}"]`)?.content;
  const userId = meta('user-id');          // موجود لديك أصلاً
  if (!userId) return;                     // فقط للمسجّلين

  const countsUrl = "{{ route('notifications.counts') }}"; // نفس المسار الموجود عندك
  const POLL_MS   = 60 * 1000;             // كل دقيقة
  const MAX_FEED  = 10;                    // أقصى عناصر نعرضها داخل القائمة

  // عناصر DOM
const badge = document.getElementById('notif-badge') || document.getElementById('notifBadge');
  const list   = document.getElementById('notifList');
  const empty  = document.getElementById('notifEmpty');
  const btnRef = document.getElementById('notifRefreshBtn');

  // مساعدات عرض
  function setBadge(n){
    if(!badge) return;
    const v = Number(n||0);
    badge.textContent = v;
    badge.classList.toggle('d-none', v <= 0);
  }

  function clearFeed(){
    if (!list) return;
    [...list.querySelectorAll('.item')].forEach(el=>el.remove());
  }

  function ensureEmptyState(){
    if(!list || !empty) return;
    const hasItems = !!list.querySelector('.item');
    empty.style.display = hasItems ? 'none' : '';
  }

  function addFeedItem({icon='bi-bell', title='', body='', href='#', at=''}){
    if(!list) return;
    const a = document.createElement('a');
    a.href = href || '#';
    a.className = 'item text-reset text-decoration-none';
    a.innerHTML = `
      <i class="bi ${icon} fs-5 mt-1"></i>
      <div class="flex-grow-1">
        <div class="title">${title}</div>
        ${body ? `<div class="small">${body}</div>` : ''}
        ${at   ? `<div class="meta">${at}</div>` : ''}
      </div>
    `;
    list.prepend(a);
    // قصّ القائمة
    const items = list.querySelectorAll('.item');
    if (items.length > MAX_FEED) items[items.length-1].remove();
    ensureEmptyState();
  }

  // نحول أرقام counts إلى بنود قابلة للعرض داخل القائمة
  function renderSyntheticFeed(d){
    clearFeed();
    const msgs = Number(d?.chats||0);
    const invs = Number(d?.invitations||0);
    const exs  = Number(d?.exchanges||0);

    if (msgs>0) addFeedItem({
      icon:'bi-chat-dots',
      title:"{{ __('auth.messages') ?? 'Messages' }}",
      body:"{{ __('notifications.new_messages') ?? 'You have new conversations to reply to.' }}",
      href:"{{ route('conversations.index') }}"
    });
    if (invs>0) addFeedItem({
      icon:'bi-envelope-open',
      title:"{{ __('auth.invitations') ?? 'Invitations' }}",
      body:"{{ __('notifications.new_invitations') ?? 'You have pending invitations.' }}",
      href:"{{ route('invitations.index') }}"
    });
    if (exs>0) addFeedItem({
      icon:'bi-arrow-left-right',
      title:"{{ __('auth.exchanges') ?? 'Exchanges' }}",
      body:"{{ __('notifications.new_exchanges') ?? 'You have exchange requests awaiting action.' }}",
      href:"{{ route('invitations.index') }}#exchanges"
    });

    ensureEmptyState();
  }

  // جلب العدّادات وتحديث الشارة + القائمة
  async function fetchCounts(){
    try{
      const r = await fetch(countsUrl, { headers:{'Accept':'application/json'} });
      const d = await r.json();
      const total = Number(d?.total||0);
      setBadge(total);
      renderSyntheticFeed(d);
    }catch(e){
      // تجاهل الخطأ بهدوء
    }
  }

  // أول تحميل + كل دقيقة
  fetchCounts();
  const poller = setInterval(fetchCounts, POLL_MS);

  // زر تحديث داخل الـDropdown
  btnRef?.addEventListener('click', (e)=>{ e.preventDefault(); fetchCounts(); });

  // === دعم Real-time (اختياري إذا Pusher مفعل) ===
  const pusherKey = meta('pusher-key');
  const cluster   = meta('pusher-cluster') || 'mt1';
  const csrf      = meta('csrf-token');

  if (window.Pusher && pusherKey) {
    const pusher = new Pusher(pusherKey, {
      cluster,
      forceTLS:true,
      authEndpoint:'/broadcasting/auth',
      auth: { headers: { 'X-CSRF-TOKEN': csrf } }
    });
    const channel = pusher.subscribe('private-App.Models.User.' + userId);

    // لما توصلك دعوة جديدة
    channel.bind('invitation.sent', (e)=>{
      setTimeout(fetchCounts, 200); // جِب الأرقام
      addFeedItem({
        icon:'bi-envelope-open',
        title: e?.title || "{{ __('auth.invitations') ?? 'Invitation' }}",
        body:  e?.body  || '',
        href:  e?.url   || "{{ route('invitations.index') }}",
        at:    e?.at    || new Date().toLocaleString()
      });
    });

    // لما توصلك رسالة جديدة
    channel.bind('message.sent', (e)=>{
      setTimeout(fetchCounts, 200);
      addFeedItem({
        icon:'bi-chat-dots',
        title:"{{ __('auth.messages') ?? 'Message' }}",
        body: (e?.from ? ("{{ __('notifications.from') ?? 'From' }}: " + e.from) : ''),
        href:  e?.url || "{{ route('conversations.index') }}",
        at:    e?.at  || new Date().toLocaleString()
      });
    });

    // تحديثات التبادلات
    channel.bind('exchange.updated', (e)=>{
      setTimeout(fetchCounts, 200);
      addFeedItem({
        icon:'bi-arrow-left-right',
        title:"{{ __('auth.exchanges') ?? 'Exchange' }}",
        body: e?.title || "{{ __('notifications.exchange_update') ?? 'Exchange updated.' }}",
        href: e?.url   || "{{ route('invitations.index') }}#exchanges",
        at:   e?.at    || new Date().toLocaleString()
      });
    });
  }
})();
</script>

</body>
</html>
