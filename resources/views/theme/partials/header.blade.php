<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maharat Hub</title>

  {{-- ====== SEO ====== --}}
  @php
    $TITLE = 'Maharat Hub';
    $DESC  = 'Connect with people globally to share knowledge, learn new skills, and exchange expertise for free.';
  @endphp
  <meta name="description" content="{{ $DESC }}">
  <meta name="keywords" content="Maharat Hub, skill exchange, share knowledge, online learning, free learning, community learning, learn skills, knowledge exchange, connect with people, global learning">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
  <meta property="og:title" content="{{ $TITLE }}">
  <meta property="og:description" content="{{ $DESC }}">
  <meta property="og:image" content="{{ asset('img/logo.png') }}">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="en_US">

  {{-- ====== CSS: Bootstrap + Bootswatch ====== --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">
  {{-- Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  {{-- ====== Custom Styles (مُصغّر + مرتب) ====== --}}
  <style>
    :root{
      --transition: all .25s ease;
      --bs-primary: #000; --bs-primary-rgb: 0,0,0;
      --nav-h: 32px;          /* أعلى/أقل حسب رغبتك */
      --nav-font: .90rem;     /* تصغير عام للخط */
      --gap-sm: .5rem;
    }
    html{ font-size: 15px; margin:0; padding:0; }           /* تصغير عام */
    body{ font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji"; }

    .header{ background: rgba(255,255,255,.97) !important; backdrop-filter: blur(6px); box-shadow: 0 2px 12px rgba(0,0,0,.05); }
    .header .headerbar{ gap: var(--gap-sm); flex-wrap: nowrap; }
    .logo { font-weight:800; font-size:1.15rem; color:#111; text-decoration:none; white-space:nowrap; }
    .logo img{ width:46px; height:34px; object-fit:cover; transition: var(--transition); }
    .logo:hover img{ transform: rotate(2deg); }

    /* روابط الدسكتوب */
    .desktop-nav .navbar-nav{ flex-wrap: nowrap !important; }
    .desktop-nav .nav-link{
      height: var(--nav-h); display:flex; align-items:center;
      padding: 0 .6rem !important; border-radius: .5rem;
      font-size: var(--nav-font); color:#2b2d42; transition: var(--transition);
      white-space: nowrap;
    }
    .desktop-nav .nav-link:hover{ color:#000; background-color: rgba(0,0,0,.04); }
    .desktop-nav .nav-link.active{
      color:#fff !important; background:#000 !important; border-radius:999px;
      box-shadow: 0 6px 18px rgba(0,0,0,.08);
    }

    /* كبسات حبوب مع عدّاد صغير */
    .pill-link{
      height: var(--nav-h); display:inline-flex; align-items:center; gap:.4rem;
      padding: 0 .6rem; border-radius: 999px; background:#f5f6f8; border:1px solid #e5e7eb;
      font-size: var(--nav-font); color:#111; text-decoration:none; font-weight:600; transition: var(--transition);
      white-space: nowrap;
    }
    .pill-link:hover{ background:#111; color:#fff; transform: translateY(-1px); }
    .pill-badge{ min-width:.9rem; height:.9rem; font-size:.65rem; padding:0 .25rem; border-radius:999px; background:#f59e0b; color:#fff; display:inline-flex; align-items:center; justify-content:center; border:2px solid #fff; }

    /* زر الجرس + البادج */
    .notif-btn{ width: var(--nav-h); height: var(--nav-h); padding:0; background:transparent; border:0; position:relative; display:inline-flex; align-items:center; justify-content:center; }
    .notif-badge{
      position:absolute; inset-block-start: 2px; inset-inline-start: 100%;
      transform: translate(-55%, 0);
      min-width:.9rem; height:.9rem; font-size:.65rem; padding:0 .25rem; border-radius:999px;
      background:#f59e0b; color:#fff; display:inline-flex; align-items:center; justify-content:center; border:2px solid #fff; z-index:5;
    }
    [dir="rtl"] .notif-badge{ transform: translate(55%, 0); }

    /* قائمة الإشعارات */
    .notif-menu{ padding:.45rem; }
    #notifList{ padding:.25rem; max-height:360px; overflow:auto; }
    #notifList .item{ display:flex; gap:.6rem; align-items:flex-start; padding:.55rem .7rem; border-radius:.5rem; transition: var(--transition); }
    #notifList .item:hover{ background:#f8f9fa; }
    #notifList .title{ font-weight:600; }
    #notifList .meta{ font-size:.75rem; color:#6c757d; }

    /* زر بريميوم مصغّر */
    .premium-pill{
      height: var(--nav-h); display:inline-flex; align-items:center; gap:.35rem;
      padding: 0 .7rem; border-radius: 999px; font-size: var(--nav-font); font-weight:700;
      background: linear-gradient(90deg,#f59e0b,#fbbf24); color:#111; border:0;
      transition: var(--transition);
    }
    .premium-pill:hover{ transform:translateY(-1px); filter: brightness(.98); }
    .premium-pill .bi-crown{ font-size:1rem; color:#111; }

    /* زِرّي اللغة (صغار جدًا) */
    .lang-switch .btn{ padding: .1rem .35rem; font-size: .75rem; line-height: 1.1; border-radius: .4rem; }
    .lang-switch .btn.active{ color:#fff; background:#000; border-color:#000; }

    /* موبايل ناڤ */
    #mobileNavToggle{ border:none; border-radius:.5rem; padding:.45rem .6rem; background:#f5f6f8; color:#111; }
    #mobileNavToggle:hover{ background:rgba(0,0,0,.08); }
    #navmenu{ overflow:hidden; transition:max-height .3s ease, padding .3s ease; max-height:0; }
    #navmenu.show{ max-height:420px; padding:.6rem 0; }

    @media (max-width:1199.98px){ .desktop-nav{ display:none; } }
    @media (min-width:1200px){ #mobileNavToggle{ display:none; } #navmenu{ display:none; } }

    /* توست بسيط بديل للإشعارات */
    #mhToast{
      position: fixed; inset-inline: 12px; inset-block-end: 12px; z-index: 2000;
      min-width: 220px; max-width: 92vw;
      display: none;
    }
    /* اخفاء أرقام الرسائل/الدعوات في الروابط تماماً */
.pill-link .pill-badge,
#m-count-chats,
#m-count-invitations,
#count-chats,
#count-invitations {
  display: none !important;
}

    
    
    
    
  </style>

  @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}">
    <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') ?? 'mt1' }}">
  @endauth

  @php
    $u = auth()->user();
    $isPremium = auth()->check() && (($u->is_premium ?? false) || (method_exists($u,'hasActiveSubscription') && $u->hasActiveSubscription()));
  @endphp

  @auth
    @unless($isPremium)
      @include('theme.conversations.modals.premium', ['modalId' => 'premiumModal', 'subscribeModalId' => 'subscribeModal'])
      @include('theme.conversations.modals.subscribe', [
        'modalId' => 'subscribeModal',
        'formId'  => 'subscribeForm',
        'config'  => ['price'=>'5 USD','wallet_email'=>'wallet@example.com','wallet_name'=>'Premium Wallet','paypal'=>'payments@yourapp.com'],
        'prefill' => ['email'=>auth()->user()->email ?? '','account_name'=>auth()->user()->name ?? 'your_account'],
      ])
    @endunless
  @endauth

  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</head>
<body>

<header id="header" class="header sticky-top border-bottom">
  <div class="container-fluid container-xl">
    <div class="headerbar d-flex align-items-center justify-content-between py-2">

      {{-- يسار: حساب/تسجيل + جرس + روابط --}}
      <div class="auth-section d-flex align-items-center gap-2 order-1">
        @auth
          {{-- حساب المستخدم --}}
          <div class="dropdown">
            <button class="btn btn-link p-0 d-flex align-items-center text-decoration-none" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
              @php
                $u        = auth()->user();
                $raw      = $u?->image_url;
                $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($u?->fullName() ?: $u?->name ?: $u?->email);
                if (!empty($raw)) {
                  if (preg_match('/^https?:\/\//i', $raw)) { $src = $raw; }
                  else { $path = ltrim($raw, '/'); $src  = (strpos($path, 'storage/') === 0) ? asset($path) : asset('storage/' . $path); }
                } else { $src = $fallback; }
                $src .= (str_contains($src, '?') ? '&' : '?') . 'v=' . ((optional($u?->updated_at)->timestamp) ?? time());
              @endphp
              <img src="{{ $src }}" alt="avatar" class="rounded-circle border" style="width:32px;height:32px;object-fit:cover" onerror="this.onerror=null; this.src='{{ $fallback }}';"/>
              <span class="d-none d-md-inline fw-semibold text-dark text-truncate mx-2" style="max-width:140px;">
                {{ auth()->user()->fullName() ?: auth()->user()->email }}
              </span>
              <i class="bi bi-chevron-down small text-muted d-none d-md-inline"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2" aria-labelledby="dropdownUser">
              <li><a class="dropdown-item d-flex align-items-center" href="{{ route('myProfile') }}"><i class="fas fa-user me-2 text-primary"></i> {{ __('auth.my_account') }}</a></li>
              <!--<li><a class="dropdown-item d-flex align-items-center" href="{{ route('invitations.index') }}"><i class="fas fa-envelope-open-text me-2 text-warning"></i> {{ __('auth.invitations') }}</a></li>-->
              <!--<li><a class="dropdown-item d-flex align-items-center" href="{{ route('conversations.index') }}"><i class="fas fa-comments me-2 text-success"></i> {{ __('auth.conversations') }}</a></li>-->
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

          {{-- الجرس --}}
          <div class="dropdown" id="notifDropdownWrap">
            <button class="notif-btn" id="notifBellBtn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="@if(app()->isLocale('ar')) الإشعارات @else Notifications @endif">
              <i class="bi bi-bell fs-6"></i>
              <span id="notif-badge" class="notif-badge d-none">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm p-0 notif-menu" aria-labelledby="notifBellBtn" style="min-width:320px">
              <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                <strong>@if(app()->isLocale('ar')) الإشعارات @else Notifications @endif</strong>
                <button type="button" class="btn btn-sm btn-link" id="notifRefreshBtn">@if(app()->isLocale('ar')) تحديث @else Refresh @endif</button>
              </div>
              <div id="notifList">
                <div class="p-3 text-muted small" id="notifEmpty">@if(app()->isLocale('ar')) لا توجد إشعارات جديدة @else No new notifications @endif</div>
              </div>
       <div class="border-top px-3 py-2 small text-center">
  <a href="{{ route('conversations.index') }}"
     class="text-decoration-none d-inline-flex align-items-center gap-1 mx-2">
    <i class="bi bi-chat-dots"></i>
    <span>@if(app()->isLocale('ar')) الرسائل @else Messages @endif</span>
  </a>

  <a href="{{ route('invitations.index') }}"
     class="text-decoration-none d-inline-flex align-items-center gap-1 mx-2">
    <i class="bi bi-envelope-open"></i>
    <span>@if(app()->isLocale('ar')) الدعوات @else Invitations @endif</span>
  </a>
</div>

            </div>
          </div>

          {{-- روابط سريعة --}}
          <nav class="d-none d-md-flex align-items-center gap-2">
            <a href="{{ route('conversations.index') }}" class="pill-link">
              <i class="bi bi-chat-left-text"></i>{{ __('auth.messages') ?? 'الرسائل' }}
              <span id="count-chats" class="pill-badge" style="display:none">0</span>
            </a>
            <a href="{{ route('invitations.index') }}" class="pill-link">
              <i class="bi bi-envelope-open"></i>{{ __('auth.invitations') ?? 'الدعوات' }}
              <span id="count-invitations" class="pill-badge" style="display:none">0</span>
            </a>
            @if(!$isPremium)
              <button class="premium-pill ms-1" data-bs-toggle="modal" data-bs-target="#premiumModal" type="button">
                <i class="bi bi-crown me-1"></i> {{ __('premium.go_premium') ?? 'Get Premium' }}
              </button>
            @endif
          </nav>
        @else
          <a class="btn btn-dark btn-sm px-3 fw-semibold shadow-sm" href="{{ route('login') }}">{{ __('auth.login_or_register') }}</a>
        @endauth
      </div>

      {{-- وسط: الشعار --}}
      <a href="{{ route('theme.index') }}" class="logo d-flex align-items-center order-4 text-decoration-none">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
      </a>

      {{-- يمين: روابط + اللغة + زر الموبايل --}}
      <div class="d-flex align-items-center gap-2 order-3">
        <div class="desktop-nav d-none d-xl-flex">
          <ul class="navbar-nav flex-row gap-1">
            <li class="nav-item"><a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">{{ __('nav.skills') }}</a></li>
            <li class="nav-item"><a href="{{ route('theme.learning_requests') }}" class="nav-link @yield('learning-requests-active')">{{ __('nav.learning_requests') }}</a></li>
            <li class="nav-item"><a href="{{ route('theme.gamification') }}" class="nav-link @yield('gamification-active')">{{ __('nav.gamification') }}</a></li>
            @guest
              <li class="nav-item"><a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">{{ __('nav.about') }}</a></li>
              <li class="nav-item"><a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">{{ __('nav.contact') }}</a></li>
            @endguest
          </ul>
        </div>

        {{-- لغة: أزرار صغار AR/EN --}}
        @php
          $urlAr = route('lang.switch','ar');
          $urlEn = route('lang.switch','en');
          $isAr  = app()->isLocale('ar');
        @endphp
        <div class="lang-switch d-none d-sm-flex align-items-center gap-1">
          <a href="{{ $urlAr }}" class="btn btn-outline-dark btn-sm {{ $isAr ? 'active' : '' }}" aria-label="Arabic">AR</a>
          <a href="{{ $urlEn }}" class="btn btn-outline-dark btn-sm {{ !$isAr ? 'active' : '' }}" aria-label="English">EN</a>
        </div>

        <button class="btn btn-outline-secondary btn-sm d-xl-none" id="mobileNavToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navmenu">
          <i class="bi bi-list"></i>
        </button>
      </div>
    </div>
  </div>

  {{-- Mobile Nav --}}
  <nav id="navmenu" class="border-top bg-white">
    <div class="container-xl py-2">
      <ul class="navbar-nav">
        <li class="nav-item"><a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">{{ __('nav.skills') }}</a></li>
        <li class="nav-item"><a href="{{ route('theme.learning_requests') }}" class="nav-link @yield('learning-requests-active')">{{ __('nav.learning_requests') }}</a></li>
        <li class="nav-item"><a href="{{ route('theme.gamification') }}" class="nav-link @yield('gamification-active')">{{ __('nav.gamification') }}</a></li>
        @guest
          <li class="nav-item"><a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">{{ __('nav.about') }}</a></li>
          <li class="nav-item"><a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">{{ __('nav.contact') }}</a></li>
        @endguest

        @auth
          <li><hr class="dropdown-divider my-2"></li>
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
              <button class="btn btn-warning w-100 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#premiumModal" type="button">
                <i class="bi bi-crown me-1"></i> {{ __('premium.go_premium') ?? 'Get Premium' }}
              </button>
            </li>
          @endif
        @endauth
      </ul>
    </div>
  </nav>
</header>

{{-- ====== بديل إشعارات بسيط (Toast) ====== --}}
<div id="mhToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body" id="mhToastBody">You have a new notification.</div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>

{{-- ====== JS (Bootstrap) ====== --}}

{{-- ====== Mobile Nav Toggle ====== --}}
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

{{-- ====== Counters + Pusher (عدادات) ====== --}}
<script>
(function(){
  "use strict";
  const meta     = n => document.querySelector(`meta[name="${n}"]`)?.content;
  const userId   = meta('user-id');
  if (!userId) return;

  const key      = meta('pusher-key');
  const cluster  = meta('pusher-cluster') || 'mt1';
  const csrf     = meta('csrf-token');

  function show(el, n){
    if (!el) return;
    el.textContent = n;
    el.style.display = (Number(n) > 0) ? '' : 'none';
  }

  const dChats = document.getElementById('count-chats');
  const dInvs  = document.getElementById('count-invitations');
  const dExch  = document.getElementById('count-exchanges');

  const mChats = document.getElementById('m-count-chats');
  const mInvs  = document.getElementById('m-count-invitations');
  const mExch  = document.getElementById('m-count-exchanges');

  const counts = { chats:0, invitations:0, exchanges:0 };
  const render = ()=>{ show(dChats,counts.chats); show(dInvs,counts.invitations); show(dExch,counts.exchanges);
                       show(mChats,counts.chats); show(mInvs,counts.invitations); show(mExch,counts.exchanges); };

  @if (Route::has('notifications.counts'))
  fetch('{{ route('notifications.counts') }}').then(r=>r.json()).then(d=>{
    counts.chats       = Number(d.chats||0);
    counts.invitations = Number(d.invitations||0);
    counts.exchanges   = Number(d.exchanges||0);
    render();
  }).catch(()=>{});
  @endif

  if (userId && key && window.Pusher) {
    const pusher = new Pusher(key, { cluster, forceTLS:true, authEndpoint:'/broadcasting/auth', auth:{ headers:{ 'X-CSRF-TOKEN': csrf } } });
    const channel = pusher.subscribe('private-App.Models.User.' + userId);
    channel.bind('invitation.sent', () => { counts.invitations++; render(); });
    channel.bind('message.sent',    () => { counts.chats++;       render(); });
    channel.bind('exchange.updated', (e)=> { if (e?.action === 'created') counts.exchanges++; render(); });
  }
})();
</script>

{{-- ====== إشعارات المتصفح + بوب-أب للموبايل ====== --}}
<script>
(function(){
  "use strict";

  const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent) || (Math.min(window.innerWidth, window.innerHeight) < 600);

  // توست مساعد
  const toastEl = document.getElementById('mhToast');
  const toast   = toastEl ? new bootstrap.Toast(toastEl, { delay: 3500 }) : null;
  function showToast(msg){
    if (!toast) return;
    document.getElementById('mhToastBody').textContent = msg || 'Notification';
    toastEl.style.display = 'block';
    toast.show();
  }

  // يفتح Modal صغير للموبايل يطلب تفعيل الإشعارات
  const NEED_KEY = 'mh_need_push_prompt_v1';
  function shouldPrompt(){
    // مرّة واحدة فقط لكل جلسة (بدّل المنطق لو حاب)
    return localStorage.getItem(NEED_KEY) !== 'no';
  }
  function markPrompted(){ localStorage.setItem(NEED_KEY, 'no'); }

  // Modal ديناميكي خفيف
  function ensureMobilePrompt(){
    if (!isMobile || !shouldPrompt() || !('Notification' in window)) return;
    // لو الإذن مُعطى/مرفوض خلاص ما نعرضه
    if (Notification.permission === 'granted' || Notification.permission === 'denied') return;

    const html = `
      <div class="modal fade" id="pushPromptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header py-2">
              <h6 class="modal-title">{{ app()->isLocale('ar') ? 'تفعيل الإشعارات' : 'Enable Notifications' }}</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body small">
              {{ app()->isLocale('ar') ? 'فعل إشعارات المتصفح عشان توصلك تنبيهات الرسائل والدعوات فوراً.' : 'Turn on browser notifications to get instant alerts for messages and invitations.' }}
            </div>
            <div class="modal-footer py-2">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ app()->isLocale('ar') ? 'لاحقاً' : 'Later' }}</button>
              <button type="button" id="btnEnablePush" class="btn btn-dark btn-sm">{{ app()->isLocale('ar') ? 'تفعيل' : 'Enable' }}</button>
            </div>
          </div>
        </div>
      </div>`;
    document.body.insertAdjacentHTML('beforeend', html);
    const modalEl = document.getElementById('pushPromptModal');
    const modal   = new bootstrap.Modal(modalEl);
    modal.show();

    modalEl.addEventListener('hidden.bs.modal', markPrompted, { once:true });

    modalEl.querySelector('#btnEnablePush')?.addEventListener('click', async ()=>{
      try{
        const perm = await Notification.requestPermission();
        if (perm === 'granted') {
          showToast(`{{ app()->isLocale('ar') ? 'تم تفعيل الإشعارات 🎉' : 'Notifications enabled 🎉' }}`);
          modal.hide();
        } else {
          showToast(`{{ app()->isLocale('ar') ? 'لم يتم تفعيل الإشعارات. سنرسل تنبيهات داخل التطبيق.' : 'Notifications were not enabled. In-app alerts will be used.' }}`);
          modal.hide();
        }
      }catch(e){
        modal.hide();
      }finally{
        markPrompted();
      }
    });
  }

  // مثال: لو وصلك بث جديد و الإذن granted، اعرض Native Notification وإلا توست
  function showNativeOrToast({title, body, url}){
    if ('Notification' in window && Notification.permission === 'granted') {
      const n = new Notification(title || 'Maharat Hub', { body: body || '', icon: '{{ asset('img/logo.png') }}' });
      if (url) { n.onclick = ()=> window.open(url, '_blank'); }
    } else {
      showToast(body || title || 'New notification');
    }
  }

  // ربطه مع البثّات (إن أحببت) — مثال بسيط:
  window.addEventListener('mh:new-event', (e)=>{
    showNativeOrToast(e.detail || {});
  });

  // شغّل البوب-أب للموبايل
  ensureMobilePrompt();
})();
</script>

{{-- ====== إشعارات القائمة (سحب دوري + عرض داخل الدروبداون) ====== --}}
<script>
(function(){
  "use strict";
  const meta  = n => document.querySelector(`meta[name="${n}"]`)?.content;
  const userId = meta('user-id');
  if (!userId) return;

  const countsUrl = "{{ route('notifications.counts') }}";
  const POLL_MS   = 60 * 1000;
  const MAX_FEED  = 10;

  const badge = document.getElementById('notif-badge');
  const list  = document.getElementById('notifList');
  const empty = document.getElementById('notifEmpty');
  const btnRef= document.getElementById('notifRefreshBtn');

  function setBadge(n){
    if(!badge) return;
    const v = Number(n||0);
    badge.textContent = v;
    badge.classList.toggle('d-none', v <= 0);
  }
  function clearFeed(){ if (!list) return; [...list.querySelectorAll('.item')].forEach(el=>el.remove()); }
  function ensureEmptyState(){
    if(!list || !empty) return;
    const hasItems = !!list.querySelector('.item');
    empty.style.display = hasItems ? 'none' : '';
  }
  function addFeedItem({icon='bi-bell', title='', body='', href='#', at=''}){
    if(!list) return;
    const a = document.createElement('a');
    a.href = href || '#'; a.className = 'item text-reset text-decoration-none';
    a.innerHTML = `<i class="bi ${icon} fs-5 mt-1"></i><div class="flex-grow-1">
        <div class="title">${title}</div>${body ? `<div class="small">${body}</div>` : ''}${at ? `<div class="meta">${at}</div>` : ''}
      </div>`;
    list.prepend(a);
    const items = list.querySelectorAll('.item'); if (items.length > MAX_FEED) items[items.length-1].remove();
    ensureEmptyState();
  }
  function renderSyntheticFeed(d){
    clearFeed();
    const msgs = Number(d?.chats||0), invs = Number(d?.invitations||0), exs = Number(d?.exchanges||0);
    if (msgs>0) addFeedItem({ icon:'bi-chat-dots', title:"{{ __('auth.messages') ?? 'Messages' }}", body:"{{ __('notifications.new_messages') ?? 'You have new conversations to reply to.' }}", href:"{{ route('conversations.index') }}" });
    if (invs>0) addFeedItem({ icon:'bi-envelope-open', title:"{{ __('auth.invitations') ?? 'Invitations' }}", body:"{{ __('notifications.new_invitations') ?? 'You have pending invitations.' }}", href:"{{ route('invitations.index') }}" });
    if (exs>0)  addFeedItem({ icon:'bi-arrow-left-right', title:"{{ __('auth.exchanges') ?? 'Exchanges' }}", body:"{{ __('notifications.new_exchanges') ?? 'You have exchange requests awaiting action.' }}", href:"{{ route('invitations.index') }}#exchanges" });
    ensureEmptyState();
  }
  async function fetchCounts(){
    try{
      const r = await fetch(countsUrl, { headers:{'Accept':'application/json'} });
      const d = await r.json();
      const total = Number(d?.total||0);
      setBadge(total);
      renderSyntheticFeed(d);
    }catch(e){}
  }

  fetchCounts();
  const poller = setInterval(fetchCounts, POLL_MS);
  btnRef?.addEventListener('click', (e)=>{ e.preventDefault(); fetchCounts(); });

  // ربط مع Pusher لعرض عنصر جديد + إطلاق حدث يعرض Native/Toast
  const pusherKey = meta('pusher-key');
  const cluster   = meta('pusher-cluster') || 'mt1';
  const csrf      = meta('csrf-token');

  function fireUIEvent(payload){
    const ev = new CustomEvent('mh:new-event', { detail: payload });
    window.dispatchEvent(ev);
  }

  if (window.Pusher && pusherKey) {
    const pusher = new Pusher(pusherKey, { cluster, forceTLS:true, authEndpoint:'/broadcasting/auth', auth:{ headers:{ 'X-CSRF-TOKEN': csrf } } });
    const channel = pusher.subscribe('private-App.Models.User.' + userId);

    channel.bind('invitation.sent', (e)=>{
      setTimeout(fetchCounts, 200);
      addFeedItem({ icon:'bi-envelope-open', title: e?.title || "{{ __('auth.invitations') ?? 'Invitation' }}", body: e?.body || '', href: e?.url || "{{ route('invitations.index') }}", at: e?.at || new Date().toLocaleString() });
      fireUIEvent({ title: 'Maharat Hub', body: "{{ app()->isLocale('ar') ? 'دعوة جديدة بانتظارك' : 'You have a new invitation' }}", url: e?.url || "{{ route('invitations.index') }}" });
    });
    channel.bind('message.sent', (e)=>{
      setTimeout(fetchCounts, 200);
      addFeedItem({ icon:'bi-chat-dots', title:"{{ __('auth.messages') ?? 'Message' }}", body: (e?.from ? ("{{ __('notifications.from') ?? 'From' }}: " + e.from) : ''), href: e?.url || "{{ route('conversations.index') }}", at: e?.at || new Date().toLocaleString() });
      fireUIEvent({ title: 'Maharat Hub', body: "{{ app()->isLocale('ar') ? 'وصلتك رسالة جديدة' : 'You received a new message' }}", url: e?.url || "{{ route('conversations.index') }}" });
    });
    channel.bind('exchange.updated', (e)=>{
      setTimeout(fetchCounts, 200);
      addFeedItem({ icon:'bi-arrow-left-right', title:"{{ __('auth.exchanges') ?? 'Exchange' }}", body: e?.title || "{{ __('notifications.exchange_update') ?? 'Exchange updated.' }}", href: e?.url || "{{ route('invitations.index') }}#exchanges", at: e?.at || new Date().toLocaleString() });
      fireUIEvent({ title: 'Maharat Hub', body: "{{ app()->isLocale('ar') ? 'تم تحديث طلب تبادل' : 'An exchange request was updated' }}", url: e?.url || "{{ route('invitations.index') }}#exchanges" });
    });
  }
})();
</script>


