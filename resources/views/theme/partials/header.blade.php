<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مهارات هب</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            transition: var(--transition);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        .logo {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .logo:hover {
            transform: scale(1.05);
            color: var(--secondary-color);
        }

        .logo img {
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
        }

        .logo:hover img {
            transform: rotate(5deg);
        }

        .nav-link {
            position: relative;
            font-weight: 600;
            color: var(--dark-color);
            padding: 0.5rem 1rem !important;
            border-radius: var(--border-radius);
            transition: var(--transition);
            margin: 0 2px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.1);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 50%;
            width: 0;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 10px;
            transition: var(--transition);
            transform: translateX(50%);
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 70%;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .dropdown-toggle::after {
            margin-right: 0.5em;
        }

        .dropdown-menu {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 0.5rem;
            margin-top: 0.75rem !important;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.7rem 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--dark-color);
            background: var(--light-color);
            transition: var(--transition);
            position: relative;
        }

        .icon-btn:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .badge-dot {
            position: absolute;
            top: 2px;
            left: 2px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            font-size: 11px;
            line-height: 18px;
            border-radius: 50%;
            background: var(--warning-color);
            color: white;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #mobileNavToggle {
            border: none;
            border-radius: var(--border-radius);
            padding: 0.6rem;
            transition: var(--transition);
        }

        #mobileNavToggle:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        #navmenu {
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            max-height: 0;
        }

        #navmenu.show {
            max-height: 300px;
            padding: 1rem 0;
        }

        .nav-open #navmenu {
            max-height: 300px;
            padding: 1rem 0;
        }

        .form-select {
            border-radius: 50px;
            padding: 0.4rem 2rem 0.4rem 0.75rem;
            border-color: #e9ecef;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .user-avatar {
            transition: var(--transition);
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        /* Animation for notification bell */
        @keyframes ring {
            0% { transform: rotate(0); }
            5% { transform: rotate(15deg); }
            10% { transform: rotate(-15deg); }
            15% { transform: rotate(15deg); }
            20% { transform: rotate(-15deg); }
            25% { transform: rotate(0); }
            100% { transform: rotate(0); }
        }

        .icon-btn.has-notification {
            animation: ring 2s ease-in-out infinite;
        }

        /* Media Queries */
        @media (max-width: 1199.98px) {
            .desktop-nav {
                display: none;
            }
        }

        @media (min-width: 1200px) {
            #mobileNavToggle {
                display: none;
            }
            
            #navmenu {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .logo span {
                font-size: 1.1rem;
            }
            
            .auth-section .btn {
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
            }
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
                                    alt="صورة" class="rounded-circle border user-avatar" style="width:38px;height:38px;object-fit:cover;">
                                <span class="d-none d-md-inline fw-semibold text-dark text-truncate mx-2" style="max-width:140px;">
                                    {{ auth()->user()->fullName() ?: auth()->user()->email }}
                                </span>
                                <i class="bi bi-chevron-down small text-muted d-none d-md-inline"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" aria-labelledby="dropdownUser">
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('myProfile') }}">
                                    <i class="fas fa-user me-2 text-primary"></i> حسابي</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('invitations.index') }}">
                                    <i class="fas fa-envelope-open-text me-2 text-warning"></i> الدعوات</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="{{ route('conversations.index') }}">
                                    <i class="fas fa-comments me-2 text-success"></i> المحادثات</a></li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">@csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
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
                            دخول / حساب جديد
                        </a>
                    @endauth
                </div>

                {{-- Center: Logo --}}
                <a href="{{ route('theme.index') }}" class="logo d-flex align-items-center order-2 text-decoration-none">
                    <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/skill-logo-design-template-6677debd608907e81c75e20c66e95baf_screen.jpg?ts=1685817469"
                        alt="Logo" class="rounded-circle border me-2" style="width:40px;height:40px;object-fit:cover;">
                </a>

                {{-- Right: Lang + Toggle + Desktop Nav --}}
                <div class="d-flex align-items-center gap-3 order-3">
                    {{-- Desktop Navigation --}}
                    <div class="desktop-nav d-none d-xl-flex">
                        <ul class="navbar-nav flex-row gap-1">
                            <li class="nav-item">
                                <a href="{{ route('theme.index') }}" class="nav-link @yield('index-active')">الرئيسية</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">معلومات عنا</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">المهارات</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">تواصل معنا</a>
                            </li>
                        </ul>
                    </div>

                    <select id="lang-select" class="form-select form-select-sm border-0 fw-semibold bg-light d-none d-sm-block" style="width:auto">
                        <option value="ar">العربية</option>
                        <option value="en">English</option>
                    </select>
                    
                    <button class="btn btn-outline-secondary d-xl-none" id="mobileNavToggle">
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
                            <i class="fas fa-home me-2"></i> الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('theme.about') }}" class="nav-link @yield('about-active')">
                            <i class="fas fa-info-circle me-2"></i> معلومات عنا
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('theme.skills') }}" class="nav-link @yield('trainers-active')">
                            <i class="fas fa-lightbulb me-2"></i> المهارات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('theme.contact') }}" class="nav-link @yield('contact-active')">
                            <i class="fas fa-envelope me-2"></i> تواصل معنا
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        (function(){
            "use strict";

            // Mobile nav toggle
            const header = document.getElementById('header');
            const toggle = document.getElementById('mobileNavToggle');
            const navMenu = document.getElementById('navmenu');
            
            if (toggle && navMenu) {
                toggle.addEventListener('click', function(){
                    header.classList.toggle('nav-open');
                    navMenu.classList.toggle('show');
                    
                    const icon = this.querySelector('i');
                    if (icon) { 
                        if (icon.classList.contains('bi-list')) {
                            icon.classList.replace('bi-list', 'bi-x');
                        } else {
                            icon.classList.replace('bi-x', 'bi-list');
                        }
                    }
                });
            }

            // Close mobile menu on link click
            document.querySelectorAll('#navmenu a').forEach(a => {
                a.addEventListener('click', () => {
                    if (header.classList.contains('nav-open')) {
                        header.classList.remove('nav-open');
                        navMenu.classList.remove('show');
                        
                        const icon = document.querySelector('#mobileNavToggle i');
                        if (icon) { 
                            icon.classList.replace('bi-x', 'bi-list');
                        }
                    }
                });
            });

            // Add animation to notification bell if there are notifications
            const invitationCount = document.getElementById('invitation-count');
            if (invitationCount) {
                const count = parseInt(invitationCount.textContent);
                if (count > 0) {
                    invitationCount.style.display = 'flex';
                    invitationCount.closest('.icon-btn').classList.add('has-notification');
                }
            }

            // Handle language selection
            const langSelect = document.getElementById('lang-select');
            if (langSelect) {
                langSelect.addEventListener('change', function() {
                    alert('تم تغيير اللغة إلى: ' + this.value);
                    // Here you would typically redirect to the language change endpoint
                });
            }

            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        })();
    </script>
</body>
</html>