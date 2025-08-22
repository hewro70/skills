<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حسابي - مهارات هاب</title>

    <link
        href="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/skill-logo-design-template-6677debd608907e81c75e20c66e95baf_screen.jpg?ts=1685817469"
        rel="icon">

    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
        }

        body {
            padding-top: 70px;
            background-color: #f8f9fc;
            color: var(--text-color);
            font-family: 'Tajawal', sans-serif;
            overflow-x: hidden;
        }

        #logo {
            max-height: 36px;
            margin-right: 8px;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 70px;
            bottom: 0;
            right: 0;
            width: 16rem;
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1050;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .main-content {
            margin-right: 16rem;
            padding: 20px;
            transition: all 0.3s;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1060;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.3);
        }

        .select2-container {
            width: 100% !important;
        }

        /* Brogress Bar */
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .progress {
            border-radius: 4px;
            background-color: #f0f0f0;
        }

        .progress-bar {
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* Mobile sidebar styles */
        @media (max-width: 991.98px) {
            .sidebar {
                right: -16rem;
            }

            .sidebar.show {
                right: 0;
            }

            .main-content {
                margin-right: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            /* Optional overlay */
            body.sidebar-open::after {
                content: '';
                position: fixed;
                top: 70px;
                right: 0;
                bottom: 0;
                left: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }
        }

        /* Other existing styles */
        .profile-section {
            display: flex;
            flex-direction: column;
        }

        .nav-link {
            color: #d1d3e2;
            padding: 1rem;
            border-left: 3px solid transparent;
        }

        .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(78, 115, 223, 0.05);
            border-left-color: var(--primary-color);
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-bar-link:hover {
            color: #333;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }

        .skill-badge {
            font-size: 0.9rem;
            margin: 0.2rem;
            padding: 0.5rem 0.8rem;
        }

        .language-level {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #e9ecef;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
        }

        /* Languages Skills */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            text-align: right;
        }

        .table td,
        .table th {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .pagination {
            margin: 0;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-right: -1px;
            line-height: 1.25;
            color: #4e73df;
            background-color: #fff;
            border: 1px solid #dddfeb;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .page-item.disabled .page-link {
            color: #b7b9cc;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dddfeb;
        }

        @media (max-width: 768px) {
            .profile-img {
                width: 100px;
                height: 100px;
            }

            .profile-section {
                flex-direction: column;
            }

            .profile-info {
                order: 2;
            }

            .profile-image {
                order: 1;
                margin-bottom: 20px;
            }
        }
    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('theme.index') }}">
                <img id="logo"
                    src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/skill-logo-design-template-6677debd608907e81c75e20c66e95baf_screen.jpg?ts=1685817469"
                    alt="العلامة التجرية">
                <i id="fas" class="fas me-2"></i>مهارات هاب
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-bar-link" id="nav-link" href="{{ route('theme.index') }}">الرئيسية</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light">
                            <i class="fas fa-sign-out-alt me-1"></i> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="pt-4 px-3">
                    <div class="text-center mb-4">
                        <img id="sidebarProfileImage" src="{{ $user->image_url }}"
                            class="rounded-circle profile-img mb-3" alt="صورة الملف الشخصي">
                        <h5 class="fw-bold">{{ $user->fullName() }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#profile-info" data-bs-toggle="tab">
                                <i class="fas fa-user me-2"></i>حسابي
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#edit-profile" data-bs-toggle="tab">
                                <i class="fas fa-edit me-2"></i>تعديل الملف
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#qualifications" data-bs-toggle="tab">
                                <i class="fas fa-graduation-cap me-2"></i>المؤهلات
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sidebar Toggle Button -->
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Main Content -->
            <div class="main-content" id="mainContent">
                <div class="tab-content">
                    <!-- Profile Info Tab -->
                    <div class="tab-pane fade show active" id="profile-info">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="fw-bold text-primary">
                                <i class="fas fa-user me-2"></i>حسابي
                            </h2>
                            <button class="btn btn-primary d-lg-none" id="mobileSidebarToggle">
                                <i class="fas fa-bars"></i> القائمة
                            </button>
                        </div>

                        <div class="row profile-section">
                            <div class="col-lg-8 d-flex align-items-center" style="min-height: 50vh;">
                                <div class="card mx-auto w-100" style="max-width: 400px;">

                                    <div class="card-body text-center">
                                        <img id="profileImage" src="{{ $user->image_url }}"
                                            class="rounded-circle profile-img mb-3" alt="صورة الملف الشخصي">
                                        <h3 class="fw-bold">{{ $user->fullName() }}</h3>
                                        <p class="text-muted mb-4">{{ $user->email }}</p>

                                        <input type="file" id="profileImageInput"
                                            accept="image/jpeg, image/png, image/gif, image/webp"
                                            style="display: none;">
                                        <div class="d-flex justify-content-center mb-2">
                                            <button class="btn btn-primary me-2" id="uploadImageBtn">
                                                <i class="fas fa-camera me-1"></i> تغيير الصورة
                                            </button>

                                            @if ($user->image_path)
                                                <button class="btn btn-danger" id="removeImageBtn">
                                                    <i class="fas fa-trash me-1"></i> إزالة الصورة
                                                </button>
                                            @endif

                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">اكتمال الملف الشخصي</span>
                                            <span
                                                class="text-primary fw-bold">{{ $user->profileCompletionPercentage() }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $user->profileCompletionPercentage() }}%"
                                                aria-valuenow="{{ $user->profileCompletionPercentage() }}"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-9 profile-info">
                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">معلومات الاتصال</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-3">
                                                    <i class="fas fa-phone me-2"></i>
                                                    <strong>الهاتف:</strong> {{ $user->phone ?? 'غير محدد' }}
                                                </p>
                                                <p class="mb-3">
                                                    <i class="fas fa-birthday-cake me-2"></i>
                                                    <strong>تاريخ الميلاد:</strong>
                                                    {{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'غير محدد' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-3">
                                                    <i class="fas fa-venus-mars me-2"></i>
                                                    <strong>الجنس:</strong>
                                                    {{ $user->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-flag me-2"></i>
                                                    <strong>البلد:</strong>
                                                    {{ $user->country->name ?? 'غير محدد' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">نبذة عني</h6>
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $user->about_me ?? 'لا يوجد وصف' }}</p>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">مهاراتي</h6>
                                    </div>
                                    <div class="card-body">
                                        @forelse($user->skills as $skill)
                                            <span class="badge bg-primary skill-badge">
                                                {{ $skill->name }}
                                            </span>
                                        @empty
                                            <p class="text-muted">لا توجد مهارات محددة</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">لغاتي</h6>
                                    </div>
                                    <div class="card-body">
                                        @forelse($user->languages as $language)
                                            <div class="mb-2">
                                                <strong>{{ $language->name }}</strong>
                                                <span
                                                    class="language-level">{{ $language->pivot->level ?? 'غير محدد' }}</span>
                                            </div>
                                        @empty
                                            <p class="text-muted">لا توجد لغات محددة</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Tab -->
                    <div class="tab-pane fade" id="edit-profile">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="fw-bold text-primary">
                                <i class="fas fa-edit me-2"></i>تعديل الملف الشخصي
                            </h2>
                            <button class="btn btn-primary d-lg-none ms-2" id="mobileSidebarToggle2">
                                <i class="fas fa-bars"></i> القائمة
                            </button>
                        </div>

                        <form id="profileForm" method="POST" action="{{ route('profile.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="card mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-white">المعلومات الأساسية</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="first_name" class="form-label">الاسم الأول</label>
                                                    <input type="text" class="form-control" id="first_name"
                                                        name="first_name"
                                                        value="{{ old('first_name', $user->first_name) }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="last_name" class="form-label">الاسم الأخير</label>
                                                    <input type="text" class="form-control" id="last_name"
                                                        name="last_name"
                                                        value="{{ old('last_name', $user->last_name) }}" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                                <input type="email" class="form-control" id="email"
                                                    name="email" value="{{ old('email', $user->email) }}" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                                    <input type="text" class="form-control" id="phone"
                                                        name="phone" value="{{ old('phone', $user->phone) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="date_of_birth" class="form-label">تاريخ
                                                        الميلاد</label>
                                                    <input type="date" class="form-control" id="date_of_birth"
                                                        name="date_of_birth"
                                                        value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="gender" class="form-label">الجنس</label>
                                                    <select class="form-select" id="gender" name="gender">
                                                        <option value="">اختر...</option>
                                                        <option value="male"
                                                            {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                                            ذكر</option>
                                                        <option value="female"
                                                            {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                                            أنثى</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="country_id" class="form-label">البلد</label>
                                                    <select class="form-select" id="country_id" name="country_id">
                                                        <option value="">اختر البلد...</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="about_me" class="form-label">نبذة عني</label>
                                                <textarea class="form-control" id="about_me" name="about_me" rows="3">{{ old('about_me', $user->about_me) }}</textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Updated Skills Section -->
                                    <div class="card mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-white">المهارات التي يمكنك تعليمها
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="skillFilterType" class="form-label">نوع
                                                        التصفية</label>
                                                    <select class="form-select" id="skillFilterType">
                                                        <option value="none">غير محدد</option>
                                                        <option value="skill">المهارة</option>
                                                        <option value="classification">الفئة</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4" id="skillNameFilterContainer"
                                                    style="display: none;">
                                                    <label for="skillNameFilter" class="form-label">بحث
                                                        بالمهارة</label>
                                                    <input type="text" class="form-control" id="skillNameFilter"
                                                        placeholder="أدخل اسم المهارة">
                                                </div>
                                                <div class="col-md-4" id="classificationFilterContainer"
                                                    style="display: none;">
                                                    <label for="classificationFilter" class="form-label">بحث
                                                        بالفئة</label>
                                                    <select class="form-select" id="classificationFilter">
                                                        <option value="">اختر الفئة...</option>
                                                        @foreach ($classifications as $classification)
                                                            <option value="{{ $classification->id }}">
                                                                {{ $classification->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="40%">المهارة</th>
                                                            <th width="40%">الفئة</th>
                                                            <th width="20%">الاختيار</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="skillsTableBody">
                                                        @foreach ($skills->forPage(1, 10) as $skill)
                                                            <tr>
                                                                <td>{{ $skill->name }}</td>
                                                                <td>{{ $skill->classification->name ?? 'غير محدد' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="skills[{{ $skill->id }}]"
                                                                        id="skill_{{ $skill->id }}"
                                                                        value="{{ $skill->id }}"
                                                                        @if ($user->skills->contains($skill->id)) checked @endif>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <p class="text-muted">عرض <span id="skillsFrom">1</span> إلى <span
                                                            id="skillsTo">10</span> من <span
                                                            id="skillsTotal">{{ $skills->count() }}</span> مهارات</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <nav aria-label="Skills pagination" class="float-end">
                                                        <ul class="pagination">
                                                            <li class="page-item disabled" id="skillsPrevPage">
                                                                <a class="page-link" href="#"
                                                                    tabindex="-1">السابقة</a>
                                                            </li>
                                                            <li class="page-item active"><a class="page-link"
                                                                    href="#">1</a></li>
                                                            @for ($i = 2; $i <= ceil($skills->count() / 10); $i++)
                                                                <li class="page-item"><a class="page-link"
                                                                        href="#">{{ $i }}</a></li>
                                                            @endfor
                                                            <li class="page-item" id="skillsNextPage">
                                                                <a class="page-link" href="#">التالية</a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Updated Languages Section -->
                                    <div class="card mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-white">لغات التواصل</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="languageFilter" class="form-label">بحث باللغة</label>
                                                    <input type="text" class="form-control" id="languageFilter"
                                                        placeholder="أدخل اسم اللغة">
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50%">اللغة</th>
                                                            <th width="30%">المستوى</th>
                                                            <th width="20%">الاختيار</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="languagesTableBody">
                                                        @foreach ($languages->forPage(1, 10) as $language)
                                                            <tr>
                                                                <td>{{ $language->name }}</td>
                                                                <td>
                                                                    <select class="form-select language-level"
                                                                        name="languages[{{ $language->id }}][level]"
                                                                        @if (!$user->languages->contains($language->id)) disabled @endif>
                                                                        <option value="">اختر المستوى...</option>
                                                                        <option value="مبتدئ جدًا"
                                                                            @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'مبتدئ جدًا' ? 'selected' : '' }} @endif>
                                                                            مبتدئ جدًا (A1)
                                                                        </option>
                                                                        <option value="مبتدئ"
                                                                            @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'مبتدئ' ? 'selected' : '' }} @endif>
                                                                            مبتدئ (A2)
                                                                        </option>
                                                                        <option value="ما قبل المتوسط"
                                                                            @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'ما قبل المتوسط' ? 'selected' : '' }} @endif>
                                                                            ما قبل المتوسط (B1)
                                                                        </option>
                                                                        <option value="متوسط"
                                                                            @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'متوسط' ? 'selected' : '' }} @endif>
                                                                            متوسط (B2)
                                                                        </option>
                                                                        <option value="فوق المتوسط"
                                                                            @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'فوق المتوسط' ? 'selected' : '' }} @endif>
                                                                            فوق المتوسط (C1)
                                                                        </option>
                                                                        <option value="متقدم جدًا"
                                                                            @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'متقدم جدًا' ? 'selected' : '' }} @endif>
                                                                            متقدم جدًا (C2)
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">
                                                                    <input class="form-check-input language-checkbox"
                                                                        type="checkbox"
                                                                        name="languages[{{ $language->id }}][selected]"
                                                                        id="language_{{ $language->id }}"
                                                                        value="1"
                                                                        @if ($user->languages->contains($language->id)) checked @endif>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <p class="text-muted">عرض <span id="languagesFrom">1</span> إلى
                                                        <span id="languagesTo">10</span> من <span
                                                            id="languagesTotal">{{ $languages->count() }}</span> لغات
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <nav aria-label="Languages pagination" class="float-end">
                                                        <ul class="pagination">
                                                            <li class="page-item disabled" id="languagesPrevPage">
                                                                <a class="page-link" href="#"
                                                                    tabindex="-1">السابقة</a>
                                                            </li>
                                                            <li class="page-item active"><a class="page-link"
                                                                    href="#">1</a></li>
                                                            @for ($i = 2; $i <= ceil($languages->count() / 10); $i++)
                                                                <li class="page-item"><a class="page-link"
                                                                        href="#">{{ $i }}</a></li>
                                                            @endfor
                                                            <li class="page-item" id="languagesNextPage">
                                                                <a class="page-link" href="#">التالية</a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Qualifications Tab -->
                    <div class="tab-pane fade" id="qualifications">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="fw-bold text-primary">
                                <i class="fas fa-graduation-cap me-2"></i>المؤهلات والخبرات
                            </h2>
                            <button class="btn btn-primary d-lg-none ms-2" id="mobileSidebarToggle3">
                                <i class="fas fa-bars"></i> القائمة
                            </button>
                        </div>

                        <form id="qualificationsForm" method="POST"
                            action="{{ route('profile.update-qualifications') }}">
                            @csrf
                            @method('PUT')

                            <div class="card mb-4" style="width: 80%">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-white">وصف المهارات والخبرات</h6>
                                </div>
                                <div class="card-body">
                                    @if ($user->skills->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach ($user->skills as $skill)
                                                <li class="list-group-item">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">{{ $skill->name }}</label>
                                                        <textarea class="form-control skill-description" name="skills[{{ $skill->id }}][description]" rows="4"
                                                            placeholder="أدخل وصفاً لمهارتك وخبراتك في هذا المجال">{{ $skill->pivot->description ?? '' }}</textarea>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="alert alert-info">
                                            لا توجد مهارات محددة. يرجى إضافة مهارات أولاً من صفحة تعديل الملف الشخصي.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($user->skills->count() > 0)
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-1"></i> حفظ التغييرات
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        async function uploadProfileImage(file) {
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'الرجاء اختيار صورة بصيغة JPG, PNG, GIF أو WEBP فقط',
                    icon: 'error',
                    confirmButtonColor: '#4e73df'
                });
                return false;
            }

            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حجم الصورة كبير جداً. الحد الأقصى 5MB',
                    icon: 'error',
                    confirmButtonColor: '#4e73df'
                });
                return false;
            }

            const formData = new FormData();
            formData.append('profile_image', file);
            formData.append('_token', '{{ csrf_token() }}');

            Swal.fire({
                title: 'جاري رفع الصورة',
                html: 'الرجاء الانتظار...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await axios.post('/profile/upload-image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                Swal.fire({
                    title: 'تم!',
                    text: 'تم تحديث صورة الملف الشخصي بنجاح',
                    icon: 'success',
                    confirmButtonColor: '#4e73df'
                });

                if (response.data.image_url) {
                    const newImageUrl = response.data.image_url + '?' + new Date().getTime();
                    $('#profileImage, #sidebarProfileImage').attr('src', newImageUrl);
                    setTimeout(() => location.reload(), 1000); // Wait 1 second before reload
                }

                location.reload();
                return true;

            } catch (error) {
                let errorMessage = 'حدث خطأ أثناء تحديث الصورة';
                if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                }
                Swal.fire({
                    title: 'خطأ!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#4e73df'
                });
                return false;
            }
        }

        $(document).ready(function() {

            $('#classificationFilter').select2({
                placeholder: "ابحث عن الفئة...",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    }
                }
            });

            $('#country_id').select2({
                placeholder: "اختر البلد...",
                dir: "rtl",
                width: '100%'
            });

            // Add this for classification filter
            $('#classificationFilter').select2({
                placeholder: "ابحث عن الفئة...",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    }
                }
            });

            // ======================
            // Profile Image Upload
            // ======================
            $('#uploadImageBtn').on('click', function(e) {
                e.preventDefault();
                $('#profileImageInput').trigger('click');
            });

            $('#profileImageInput').on('change', function() {
                if (this.files && this.files[0]) {
                    uploadProfileImage(this.files[0]);
                }
            });

            // ======================
            // Sidebar Functionality
            // ======================
            const sidebar = $('#sidebar');
            const sidebarToggle = $('#sidebarToggle');
            const mobileSidebarToggle = $('#mobileSidebarToggle');
            const mobileSidebarToggle2 = $('#mobileSidebarToggle2');

            function toggleSidebar() {
                sidebar.toggleClass('show');
                $('body').toggleClass('sidebar-open');
            }

            function initSidebar() {
                if ($(window).width() < 992) {
                    sidebar.removeClass('show');
                } else {
                    sidebar.addClass('show');
                }
            }

            initSidebar();

            [sidebarToggle, mobileSidebarToggle, mobileSidebarToggle2].forEach(btn => {
                btn.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSidebar();
                });
            });

            $(document).on('click', function(e) {
                if ($(window).width() < 992 &&
                    !$(e.target).closest(
                        '#sidebar, #sidebarToggle, #mobileSidebarToggle, #mobileSidebarToggle2').length &&
                    sidebar.hasClass('show')) {
                    toggleSidebar();
                }
            });

            $(window).on('resize', initSidebar);

            // ======================
            // Skills Functionality
            // ======================
            const skillsPerPage = 10;
            let currentSkillsPage = 1;
            let filteredSkills = @json($skills);
            let allSelectedSkills = @json($user->skills->pluck('id')->toArray()) || [];

            $('#skillFilterType').change(function() {
                const filterType = $(this).val();
                $('#skillNameFilterContainer').toggle(filterType === 'skill');
                $('#classificationFilterContainer').toggle(filterType === 'classification');

                if (filterType === 'none') {
                    filteredSkills = @json($skills);
                    currentSkillsPage = 1;
                    renderSkillsTable();
                }
            });

            $('#skillNameFilter').keyup(function() {
                const searchTerm = $(this).val().toLowerCase();
                filteredSkills = @json($skills).filter(skill =>
                    skill.name.toLowerCase().includes(searchTerm)
                );
                currentSkillsPage = 1;
                renderSkillsTable();
            });

            $('#classificationFilter').change(function() {
                const classificationId = $(this).val();
                if (!classificationId) {
                    filteredSkills = @json($skills);
                } else {
                    filteredSkills = @json($skills).filter(skill =>
                        skill.classification_id == classificationId
                    );
                }
                currentSkillsPage = 1;
                renderSkillsTable();
            });

            $(document).on('change', '.form-check-input[type="checkbox"][name^="skills"]', function() {
                const skillId = parseInt($(this).val());
                if ($(this).is(':checked')) {
                    if (!allSelectedSkills.includes(skillId)) {
                        allSelectedSkills.push(skillId);
                    }
                } else {
                    allSelectedSkills = allSelectedSkills.filter(id => id !== skillId);
                }
            });

            $(document).on('click', '.skills-page-link', function(e) {
                e.preventDefault();
                currentSkillsPage = parseInt($(this).data('page'));
                renderSkillsTable();
            });

            $(document).on('click', '#skillsPrevPage', function(e) {
                e.preventDefault();
                if (currentSkillsPage > 1) {
                    currentSkillsPage--;
                    renderSkillsTable();
                }
            });

            $(document).on('click', '#skillsNextPage', function(e) {
                e.preventDefault();
                if (currentSkillsPage < Math.ceil(filteredSkills.length / skillsPerPage)) {
                    currentSkillsPage++;
                    renderSkillsTable();
                }
            });

            function renderSkillsTable() {
                const startIndex = (currentSkillsPage - 1) * skillsPerPage;
                const paginatedSkills = filteredSkills.slice(startIndex, startIndex + skillsPerPage);

                let html = '';
                paginatedSkills.forEach(skill => {
                    html += `
                <tr>
                    <td>${skill.name}</td>
                    <td>${skill.classification ? skill.classification.name : 'غير محدد'}</td>
                    <td class="text-center">
                        <input class="form-check-input" type="checkbox"
                            name="skills[${skill.id}]"
                            id="skill_${skill.id}"
                            value="${skill.id}"
                            ${allSelectedSkills.includes(skill.id) ? 'checked' : ''}>
                    </td>
                </tr>`;
                });

                $('#skillsTableBody').html(html);
                updateSkillsPaginationInfo();
            }

            function updateSkillsPaginationInfo() {
                const startIndex = (currentSkillsPage - 1) * skillsPerPage;
                $('#skillsFrom').text(startIndex + 1);
                $('#skillsTo').text(Math.min(startIndex + skillsPerPage, filteredSkills.length));
                $('#skillsTotal').text(filteredSkills.length);

                const totalPages = Math.ceil(filteredSkills.length / skillsPerPage);
                let paginationHtml = `
            <li class="page-item ${currentSkillsPage === 1 ? 'disabled' : ''}" id="skillsPrevPage">
                <a class="page-link" href="#" tabindex="-1">السابقة</a>
            </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    paginationHtml += `
                <li class="page-item ${i === currentSkillsPage ? 'active' : ''}">
                    <a class="page-link skills-page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
                }

                paginationHtml += `
            <li class="page-item ${currentSkillsPage === totalPages ? 'disabled' : ''}" id="skillsNextPage">
                <a class="page-link" href="#">التالية</a>
            </li>`;

                $('.pagination').first().html(paginationHtml);
            }

            // ======================
            // Languages Functionality
            // ======================
            const languagesPerPage = 10;
            let currentLanguagesPage = 1;
            let filteredLanguages = @json($languages);
            let allLanguagesData = @json($languages);
            let selectedLanguages = @json(
                $user->languages->mapWithKeys(function ($lang) {
                        return [$lang->id => ['selected' => true, 'level' => $lang->pivot->level]];
                    })->toArray()) || {};

            @foreach ($user->languages as $lang)
                selectedLanguages[{{ $lang->id }}] = {
                    selected: true,
                    level: '{{ $lang->pivot->level }}'
                };
            @endforeach

            renderSkillsTable();
            renderLanguagesTable();

            $('#languageFilter').keyup(function() {
                const searchTerm = $(this).val().toLowerCase();
                filteredLanguages = allLanguagesData.filter(language =>
                    language.name.toLowerCase().includes(searchTerm)
                );
                currentLanguagesPage = 1;
                renderLanguagesTable();
            });

            $(document).on('click', '.languages-page-link', function(e) {
                e.preventDefault();
                currentLanguagesPage = parseInt($(this).data('page'));
                renderLanguagesTable();
            });

            $(document).on('click', '#languagesPrevPage', function(e) {
                e.preventDefault();
                if (currentLanguagesPage > 1) {
                    currentLanguagesPage--;
                    renderLanguagesTable();
                }
            });

            $(document).on('click', '#languagesNextPage', function(e) {
                e.preventDefault();
                if (currentLanguagesPage < Math.ceil(filteredLanguages.length / languagesPerPage)) {
                    currentLanguagesPage++;
                    renderLanguagesTable();
                }
            });

            $(document).on('change', '.language-checkbox', function() {
                const row = $(this).closest('tr');
                const levelSelect = row.find('.language-level');
                const languageId = parseInt($(this).val());

                if ($(this).is(':checked')) {
                    if (!levelSelect.val()) {
                        levelSelect.val('مبتدئ');
                    }
                    levelSelect.prop('disabled', false);
                    selectedLanguages[languageId] = {
                        selected: true,
                        level: levelSelect.val()
                    };
                } else {
                    levelSelect.prop('disabled', true);
                    delete selectedLanguages[languageId];
                }
            });

            $(document).on('change', '.language-level', function() {
                const languageId = parseInt($(this).closest('tr').find('.language-checkbox').val());
                if (selectedLanguages[languageId]) {
                    selectedLanguages[languageId].level = $(this).val();
                }
            });

            function renderLanguagesTable() {
                const startIndex = (currentLanguagesPage - 1) * languagesPerPage;
                const paginatedLanguages = filteredLanguages.slice(startIndex, startIndex + languagesPerPage);

                let html = '';
                paginatedLanguages.forEach(language => {
                    const isSelected = selectedLanguages.hasOwnProperty(language.id);
                    const level = isSelected ? selectedLanguages[language.id].level : '';

                    html += `
                <tr>
                    <td>${language.name}</td>
                    <td>
                        <select class="form-select language-level" 
                            name="languages[${language.id}][level]"
                            ${isSelected ? '' : 'disabled'}>
                            <option value="">اختر المستوى...</option>
                            <option value="مبتدئ جدًا" ${level === 'مبتدئ جدًا' ? 'selected' : ''}>
                                مبتدئ جدًا (A1)
                            </option>
                            <option value="مبتدئ" ${level === 'مبتدئ' ? 'selected' : ''}>
                                مبتدئ (A2)
                            </option>
                            <option value="ما قبل المتوسط" ${level === 'ما قبل المتوسط' ? 'selected' : ''}>
                                ما قبل المتوسط (B1)
                            </option>
                            <option value="متوسط" ${level === 'متوسط' ? 'selected' : ''}>
                                متوسط (B2)
                            </option>
                            <option value="فوق المتوسط" ${level === 'فوق المتوسط' ? 'selected' : ''}>
                                فوق المتوسط (C1)
                            </option>
                            <option value="متقدم جدًا" ${level === 'متقدم جدًا' ? 'selected' : ''}>
                                متقدم جدًا (C2)
                            </option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input class="form-check-input language-checkbox" type="checkbox"
                            name="languages[${language.id}][selected]"
                            id="language_${language.id}" value="${language.id}"
                            ${isSelected ? 'checked' : ''}>
                    </td>
                </tr>`;
                });

                $('#languagesTableBody').html(html);
                updateLanguagesPaginationInfo();
            }

            function updateLanguagesPaginationInfo() {
                const startIndex = (currentLanguagesPage - 1) * languagesPerPage;
                $('#languagesFrom').text(startIndex + 1);
                $('#languagesTo').text(Math.min(startIndex + languagesPerPage, filteredLanguages.length));
                $('#languagesTotal').text(filteredLanguages.length);

                const totalPages = Math.ceil(filteredLanguages.length / languagesPerPage);
                let paginationHtml = `
                <li class="page-item ${currentLanguagesPage === 1 ? 'disabled' : ''}" id="languagesPrevPage">
                    <a class="page-link" href="#" tabindex="-1">السابقة</a>
                </li>`;

                    for (let i = 1; i <= totalPages; i++) {
                        paginationHtml += `
                    <li class="page-item ${i === currentLanguagesPage ? 'active' : ''}">
                        <a class="page-link languages-page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                    }

                    paginationHtml += `
                <li class="page-item ${currentLanguagesPage === totalPages ? 'disabled' : ''}" id="languagesNextPage">
                    <a class="page-link" href="#">التالية</a>
                </li>`;

                $('.pagination').last().html(paginationHtml);
            }

            // ======================
            // Form Submission
            // ======================

            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                // Clear previous dynamic inputs
                $('.dynamic-skill-input').remove();
                $('.dynamic-language-input').remove();

                // Validate languages before submission
                const invalidLanguages = [];
                const validLanguagesData = {};

                Object.keys(selectedLanguages).forEach(langId => {
                    const langIdNum = parseInt(langId);
                    if (isNaN(langIdNum) || langIdNum <= 0) return;

                    if (!selectedLanguages[langId].level) {
                        const langName = allLanguagesData.find(l => l.id == langId)?.name ||
                            'Unknown';
                        invalidLanguages.push(langName);
                    } else {
                        validLanguagesData[langId] = {
                            selected: true,
                            level: selectedLanguages[langId].level
                        };
                    }
                });

                if (invalidLanguages.length > 0) {
                    Swal.fire({
                        title: 'خطأ',
                        html: `الرجاء تحديد مستوى للغات التالية:<br>${invalidLanguages.join('<br>')}`,
                        icon: 'error',
                        confirmButtonColor: '#4e73df'
                    });
                    return false;
                }

                // Add skills input
                $('<input>')
                    .attr({
                        type: 'hidden',
                        name: 'skills_data',
                        value: JSON.stringify(allSelectedSkills.filter(id => id > 0)),
                        class: 'dynamic-skill-input'
                    })
                    .appendTo(form);

                // Add validated languages input
                $('<input>')
                    .attr({
                        type: 'hidden',
                        name: 'languages_data',
                        value: JSON.stringify(validLanguagesData),
                        class: 'dynamic-language-input'
                    })
                    .appendTo(form);

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيتم حفظ التغييرات التي أجريتها على ملفك الشخصي",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، احفظ',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $('#country_id').select2({
                placeholder: "اختر البلد...",
                dir: "rtl",
                width: '100%'
            });


            @if (session('success'))
                Swal.fire({
                    title: 'تم!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#4e73df'
                });
            @endif

            // ======================
            // Qualifications Form Submission
            // ======================
            $('#qualificationsForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيتم حفظ التغييرات التي أجريتها على مؤهلاتك",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، احفظ',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading indicator
                        Swal.fire({
                            title: 'جاري الحفظ',
                            html: 'الرجاء الانتظار...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form via AJAX
                        $.ajax({
                            url: form.action,
                            method: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    title: 'تم!',
                                    text: 'تم تحديث المؤهلات بنجاح',
                                    icon: 'success',
                                    confirmButtonColor: '#4e73df'
                                });
                            },
                            error: function(xhr) {
                                let errorMessage = 'حدث خطأ أثناء حفظ التغييرات';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'خطأ!',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeBtn = document.getElementById('removeImageBtn');
            const profileImage = document.getElementById('profileImage'); // preview image
            const sidebarImage = document.getElementById('sidebarProfileImage'); // fixed ID

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'هل انت متأكد من ازالة الصورة؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'تأكيد',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('profile.remove-image') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({})
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Use gender from server
                                        const userGender = '{{ auth()->user()->gender }}';
                                        const defaultImage = userGender === 'female' ?
                                            'https://cdn-icons-png.flaticon.com/512/4140/4140047.png' :
                                            'https://cdn-icons-png.flaticon.com/512/4140/4140048.png';

                                        // Update preview and sidebar image
                                        if (profileImage) profileImage.src = defaultImage;
                                        if (sidebarImage) sidebarImage.src = defaultImage +
                                            '?v=' + new Date().getTime();

                                        // Reset file input if needed
                                        const fileInput = document.getElementById('imageInput');
                                        if (fileInput) fileInput.value = '';

                                        Swal.fire('تم الحذف!', 'تمت إزالة الصورة بنجاح.',
                                            'success');
                                    } else {
                                        Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف.', 'error');
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('خطأ!', 'حدث خطأ في الاتصال بالخادم.', 'error');
                                });
                        }
                    });
                });
            }
        });
    </script>



</body>

</html>
