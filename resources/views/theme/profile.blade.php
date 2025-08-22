@extends('theme.master')
@section('content')
    <div class="profile-page rtl" dir="rtl">
        @include('theme.partials.heroSection', [
            'title' => 'الملف الشخصي',
            'description' => 'معلومات الموهبة',
            'breadcrumbs' => [
                [
                    'title' => 'المهارات',
                    'url' => route('theme.skills'),
                ],
                [
                    'title' => 'الملف الشخصي',
                    'url' => '',
                ],
            ],
        ])

        <div class="container py-4">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="profile-card">
                        <!-- Profile Header with Right-Aligned Image -->
                        <div class="profile-header d-flex flex-row-reverse justify-content-between align-items-start">
                            <div class="profile-info flex-grow-1">
                                <h2 class="profile-name mb-1">{{ $user->fullName() }}</h2>
                                <p class="profile-location mb-2 text-muted">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $user->country->name ?? 'غير محدد' }}
                                </p>
                                <div class="profile-meta d-flex align-items-center gap-3">
                                    <span class="text-success">
                                        <i class="bi bi-circle-fill" style="font-size: 0.6rem;"></i> متاح الآن
                                    </span>
                                    <span class="text-primary">
                                        <i class="bi bi-star-fill"></i> أعلى تقييم
                                    </span>
                                    <span class="text-muted">
                                        {{ now()->format('h:i a') }} التوقيت المحلي
                                    </span>
                                </div>
                                <div class="profile-rating mt-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-success">
                                            <i class="bi bi-check-circle-fill"></i>
                                            {{ $user->receivedInvitations()->where('reply', 'قبول')->count() }} مقبولة
                                        </span>
                                        <span class="text-danger">
                                            <i class="bi bi-x-circle-fill"></i>
                                            {{ $user->receivedInvitations()->where('reply', 'رفض')->count() }} مرفوضة
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Right-Aligned Profile Image -->
                            <div class="profile-image-container ms-3">
                                <img src="{{ $user->getImageUrlAttribute() }}" alt="Profile" class="profile-avatar"
                                    style="width: 10rem; height: 10rem;">
                            </div>
                        </div>

                        <!-- Rest of your content remains the same -->
                        <div class="profile-section mt-4">
                            <h4 class="section-title">نبذة عني</h4>
                            <div class="section-content">
                                <p class="mb-0">{{ $user->about_me ?? 'لا يوجد وصف' }}</p>
                            </div>
                        </div>

                        <div class="profile-section">
                            <h4 class="section-title">المهارات</h4>
                            <div class="section-content">
                                @if ($user->skills->count() > 0)
                                    <ul class="skills-list list-unstyled column row-cols-2 row-cols-md-3 g-2">
                                        @foreach ($user->skills as $skill)
                                            <li class="col">
                                                <div class="skill-item p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="skill-name">{{ $skill->name }}</span>
                                                        <span
                                                            class="badge bg-primary">{{ $skill->classification->name }}</span>
                                                    </div>
                                                    @if ($skill->pivot->description)
                                                        <p class="skill-description text-muted mb-0 small">
                                                            {{ $skill->pivot->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">لا توجد مهارات مسجلة</p>
                                @endif
                            </div>
                        </div>

                        <div class="profile-section">
                            <h4 class="section-title">اللغات</h4>
                            <div class="section-content">
                                @if ($user->languages->count() > 0)
                                    <div class="languages-container">
                                        @foreach ($user->languages as $language)
                                            <div
                                                class="language-item d-flex justify-content-between align-items-center mb-2">
                                                <span>{{ $language->name }}</span>
                                                <div class="language-level">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="bi bi-star-fill {{ $i <= $language->pivot->level ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">لا توجد لغات مسجلة</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Profile Page Styles */
        .profile-page {
            background-color: #f8f9fa;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        /* Profile Header */
        .profile-header {
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-direction: row-reverse;
        }

        /* Profile Image - Right Aligned and Small */
        .profile-image-container {
            margin-left: 1rem;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-name {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.25rem;
            font-size: 1.25rem;
        }

        .profile-location {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .profile-meta {
            font-size: 0.8rem;
            margin: 0.5rem 0;
        }

        .section-title {
            color: #0d6efd;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .skills-list {
            margin: 0 -0.5rem;
        }

        .skill-item {
            background-color: #f8f9fa;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .skill-item:hover {
            background-color: #e9ecef;
        }

        .skill-name {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .skill-description {
            font-size: 0.8rem;
        }

        /* Languages */
        .language-level {
            font-size: 0.9rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-image-container {
                width: 60px;
                height: 60px;
            }

            .profile-name {
                font-size: 1.1rem;
            }

            .skills-list {
                row-cols: 2;
            }
        }

        @media (max-width: 576px) {
            .profile-avatar {
                width: 60px;
                height: 60px;
            }

            .profile-card {
                padding: 1rem;
            }

            .profile-image-container {
                width: 50px;
                height: 50px;
                margin-left: 1rem;
            }

            .profile-header {
                flex-direction: row-reverse;
            }

            .skills-list {
                row-cols: 1;
            }
        }
    </style>
@endsection
