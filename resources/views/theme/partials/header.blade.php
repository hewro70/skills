<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('app.title') }}</title>

  <!-- Bootswatch (Lux) -->
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">

  <!-- Primary أسود -->
  <style>
    :root{ --bs-primary:#000; --bs-primary-rgb:0,0,0; }
    .btn-primary{
      background-color:var(--bs-primary)!important; border-color:var(--bs-primary)!important;
      border-radius:999px; padding:.5rem 1.25rem; font-weight:600; transition:all .25s ease;
    }
    .btn-primary:hover,.btn-primary:focus{
      background-color:#111!important; border-color:#111!important;
      transform:translateY(-1px); box-shadow:0 .5rem 1rem rgba(0,0,0,.12);
    }
  </style>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <style>
    :root{ --transition:all .3s ease; }
    body{ font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

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

    /* ========== NAV LINKS (تم إصلاح الـ Active) ========== */
    .desktop-nav .nav-link{
      position:relative; font-weight:600; color:#2b2d42;
      padding:.5rem .9rem!important; border-radius:.6rem; transition:var(--transition);
      margin:0 2px;
    }
    /* Hover: خط سفلي فقط، بدون خلفية */
    .desktop-nav .nav-link:hover{ color:#000; background-color:transparent; }
    .desktop-nav .nav-link::after{
      content:''; position:absolute; inset-inline-start:50%; inset-block-end:0; transform:translateX(-50%);
      width:0; height:2px; background:currentColor; opacity:.35; border-radius:10px; transition:width .22s ease, opacity .22s ease;
    }
    .desktop-nav .nav-link:hover::after{ width:80%; opacity:.8; }

    /* Active: كبسولة سوداء + نص أبيض، شيل الخط السفلي */
    header .desktop-nav .nav-link.active{
      color:#fff!important; background:#000!important; border-radius:999px; box-shadow:0 6px 18px rgba(0,0,0,.08);
    }
    header .desktop-nav .nav-link.active::after{ width:0!important; }

    /* Dropdown أنعم */
    .dropdown-menu{
      border:none; border-radius:.8rem;
      box-shadow:0 10px 30px rgba(0,0,0,.08);
      padding:.5rem;
    }
    .dropdown-item{
      border-radius:.5rem; padding:.6rem .9rem; font-weight:500; transition:var(--transition);
    }
    .dropdown-item:hover{ background:rgba(0,0,0,.06); color:#111; }

    /* أيقونة الجرس + البادج */
    .icon-btn{
      width:40px; height:40px; display:flex; align-items:center; justify-content:center;
      border-radius:50%; color:#111; background:#f5f6f8; transition:var(--transition); position:relative;
    }
    .icon-btn:hover{ background:#111; color:#fff; transform:translateY(-2px); }
    .badge-dot{
      position:absolute; top:2px; inset-inline-start:2px;
      min-width:18px; height:18px; padding:0 4px; font-size:11px; line-height:18px;
      border-radius:50%; background:#f59e0b; color:#fff; border:2px solid #fff;
      display:flex; align-items:center; justify-content:center;
    }

    /* مبدّل اللغة */
    #lang-select{
      border:0; background:#f5f6f8; border-radius:999px;
      padding:.35rem .9rem; font-weight:600; cursor:pointer;
    }
    #lang-select:focus{ box-shadow:0 0 0 .2rem rgba(0,0,0,.08); }

    /* زر القائمة للموبايل */
    #mobileNavToggle{
      border:none; border-radius:.6rem; padding:.55rem .7rem;
      background:#f5f6f8; color:#111; transition:var(--transition);
    }
    #mobileNavToggle:hover{ background:rgba(0,0,0,.08); }

    /* قائمة الموبايل + Active موحّد */
    #navmenu{ overflow:hidden; transition:max-height .35s ease, padding .35s ease; max-height:0; }
    #navmenu.show{ max-height:320px; padding:.8rem 0; }
    .nav-open #navmenu{ max-height:320px; padding:.8rem 0; }

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
  </style>
</head>
<body>

<header id="header" class="header sticky-top bg-white border-bottom">
  <div class="container-fluid container-xl">
    <div class="headerbar d-flex align-items-center justify-content-between py-2">

      {{-- Left: User/Auth --}}
      <div class="auth-section d-flex align-items-center gap-3 order-1">
        @auth
          <div class="dropdown">
            <button class="btn btn-link p-0 d-flex align-items-center text-decoration-none" id="dropdownUser"
                    data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ auth()->user()->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                   alt="avatar" class="rounded-circle border user-avatar" style="width:38px;height:38px;object-fit:cover;">
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

          {{-- Bell --}}
          <a href="{{ route('invitations.index') }}" class="icon-btn position-relative">
            <i class="fas fa-bell"></i>
            <span id="invitation-count" class="badge-dot">3</span>
          </a>
        @else
          <a class="btn btn-primary px-3 fw-semibold shadow-sm" href="{{ route('login') }}">
            {{ __('auth.login_or_register') }}
          </a>
        @endauth
      </div>

      {{-- Center: Logo --}}
      <a href="{{ route('theme.index') }}" class="logo d-flex align-items-center order-4 text-decoration-none">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
      </a>

      {{-- Right: Desktop Nav + Lang --}}
      <div class="d-flex align-items-center gap-3 order-3">
        <div class="desktop-nav d-none d-xl-flex">
          <ul class="navbar-nav flex-row gap-1">
            <li class="nav-item">
              <a href="{{ route('theme.index') }}" class="nav-link @yield('index-active')">{{ __('nav.home') }}</a>
            </li>
            {{-- أمثلة:
            <li class="nav-item"><a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">{{ __('nav.about') }}</a></li>
            <li class="nav-item"><a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">{{ __('nav.skills') }}</a></li>
            <li class="nav-item"><a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">{{ __('nav.contact') }}</a></li>
            --}}
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

        <button class="btn btn-outline-secondary d-xl-none" id="mobileNavToggle" aria-label="Toggle navigation">
          <i class="bi bi-list"></i>
        </button>
      </div>
    </div>
  </div>

  {{-- Mobile Nav --}}
  <nav id="navmenu" class="border-top bg-white">
    <div class="container-xl py-2">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="{{ route('theme.index') }}" class="nav-link @yield('index-active')">
            <i class="fas fa-home me-2"></i> {{ __('nav.home') }}
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>

<script>
(function(){
  "use strict";
  const sel = document.getElementById('lang-select');
  if (sel) sel.addEventListener('change', function () {
    const url = this.value === 'ar' ? this.dataset.urlAr : this.dataset.urlEn;
    if (url) window.location.href = url;
  });

  const header = document.getElementById('header');
  const toggle = document.getElementById('mobileNavToggle');
  const navMenu = document.getElementById('navmenu');
  if (toggle && navMenu) {
    toggle.addEventListener('click', function(){
      header.classList.toggle('nav-open');
      navMenu.classList.toggle('show');
      const icon = this.querySelector('i');
      if (icon) { icon.classList.toggle('bi-x'); icon.classList.toggle('bi-list'); }
    });
  }

  const invitationCount = document.getElementById('invitation-count');
  if (invitationCount && parseInt(invitationCount.textContent) > 0) {
    invitationCount.style.display = 'flex';
    invitationCount.closest('.icon-btn')?.classList.add('has-notification');
  }
})();
</script>
</body>
</html>
