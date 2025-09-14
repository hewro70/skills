<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>لوحة تحكم المسؤول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* الخطوط والألوان */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }

        /* الشريط الجانبي */
        #sidebar {
            width: 25%;
            background: #212529;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.25rem;
            box-shadow: 3px 0 8px rgba(0,0,0,0.15);
        }
        #sidebar .navbar-brand {
            font-weight: 800;
            font-size: 1.75rem;
            color: white;
            margin-bottom: 2.5rem;
            text-align: center;
            user-select: none;
            letter-spacing: 1.2px;
        }
        #sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1.25rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
            user-select: none;
            font-weight: 600;
        }
        #sidebar .nav-link.active,
        #sidebar .nav-link:hover {
            background-color: #ffc107;
            color: #212529 !important;
            text-decoration: none;
            box-shadow: 0 0 8px #ffc107aa;
        }
        #sidebar ul.nav-pills {
            flex-grow: 1;
            gap: 0.5rem;
            display: flex;
            flex-direction: column;
        }
        #sidebar hr {
            border-color: #495057;
            margin: 1.5rem 0;
        }
        #sidebar form button.nav-link {
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }
        #sidebar form button.nav-link:hover {
            background-color: #dc3545;
            color: #fff;
        }
        #sidebar > .text-center {
            font-size: 0.9rem;
            margin-top: auto;
            color: #6c757d;
            user-select: none;
            text-align: center;
            padding-top: 1rem;
        }

        /* محتوى الصفحة */
        #content {
            flex-grow: 1;
            padding: 2.5rem 3rem;
            height: 100vh;
            overflow-y: auto;
            background-color: #fff;
            box-shadow: inset 0 0 10px #00000010;
            border-radius: 0 1rem 1rem 0;
        }
        #content::-webkit-scrollbar {
            width: 10px;
        }
        #content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        #content::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 5px;
        }
        #content::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }

        /* شريط التنقل الأعلى */
        nav.navbar {
            background-color: #212529;
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            user-select: none;
            box-shadow: 0 2px 8px rgb(33 37 41 / 0.3);
        }
        nav.navbar .navbar-text {
            color: white;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
        }

        /* استجابة للهواتف */
        @media (max-width: 768px) {
            #sidebar {
                width: 60px;
                padding: 1rem 0.5rem;
            }
            #sidebar .navbar-brand,
            #sidebar ul.nav-pills li a {
                font-size: 0;
                padding: 0.5rem 0;
            }
            #sidebar .nav-link::before {
                content: attr(title);
                position: absolute;
                left: 100%;
                top: 50%;
                transform: translateY(-50%);
                background: #212529;
                color: white;
                padding: 5px 10px;
                border-radius: 0.5rem;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            #sidebar .nav-link:hover::before {
                opacity: 1;
            }
        }
        #sidebar .nav-link.active, #sidebar .nav-link:hover {
            background-color: #ffffff;
            color: #000000 !important;
            text-decoration: none;
            box-shadow: 0 0 8px #ededed00;
        }

      a.navbar-brand:focus-visible {
    outline: none;
}

    </style>
</head>
<body>
    <div class="d-flex" style="height: 100vh;">

        <!-- Sidebar -->
        <nav id="sidebar" aria-label="القائمة الجانبية">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand" aria-label="الرئيسية">
                الإدارة
            </a>
            <ul class="nav nav-pills flex-column mb-auto" role="menu">
                <li class="nav-item" role="none">
                    <a href="{{ route('admin.dashboard') }}" title="لوحة التحكم" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" role="menuitem">
                        لوحة التحكم
                    </a>
                </li>
                <li role="none">
                    <a href="{{ route('admin.users.index') }}" title="المستخدمون" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" role="menuitem">
                        المستخدمون
                    </a>
                </li>
             <li role="none">
    <a href="{{ route('admin.skills-categories.index') }}"
       title="المهارات والتصنيفات"
       class="nav-link {{ request()->routeIs('admin.skills-categories.*') ? 'active' : '' }}"
       role="menuitem">
        المهارات والتصنيفات
    </a>
</li>
<li role="none">
    <a href="{{ route('admin.premium-requests.index') }}"
       title="طلبات البريميوم"
       class="nav-link {{ request()->routeIs('admin.premium-requests.*') ? 'active' : '' }}"
       role="menuitem">
        طلبات البريميوم
    </a>
</li>


                <li role="none">
                    <a href="{{ route('admin.profile') }}" title="الملف الشخصي" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" role="menuitem">
                        الملف الشخصي
                    </a>
                </li>
                <li>
                      <form method="POST" action="{{ route('logout') }}" role="none">
                @csrf
                <button type="submit" class="nav-link text-start btn btn-link text-danger p-2 fw-bold" style="text-decoration: none;" aria-label="تسجيل الخروج">
                    تسجيل الخروج
                </button>
            </form>
                </li>
            </ul>

            <hr />

          
        </nav>

        <!-- Page Content -->
        <main id="content" tabindex="0">
            <nav class="navbar navbar-expand navbar-dark">
                <div class="container-fluid px-0">
                    <span class="navbar-text">لوحة تحكم المسؤول</span>
                </div>
            </nav>

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
