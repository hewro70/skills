@extends('theme.master')

@section('content')
<div class="profile-page rtl" dir="rtl">
  <div class="container py-4">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="profile-card">

          {{-- Header --}}
          <div class="profile-header d-flex flex-row-reverse justify-content-between align-items-start">
            <div class="profile-info flex-grow-1">
              <h2 class="profile-name mb-1">{{ $user->fullName() }}</h2>

              <p class="profile-location mb-2 text-muted">
                <i class="bi bi-geo-alt-fill me-1"></i>
                {{ $user->country->name ?? __('profile.not_specified') }}
              </p>

              <div class="profile-meta d-flex align-items-center flex-wrap gap-3">
                <span class="chip chip-success">
                  <i class="bi bi-circle-fill"></i> {{ __('profile.available_now') }}
                </span>
                <span class="chip chip-primary">
                  <i class="bi bi-star-fill"></i> {{ __('profile.top_rated') }}
                </span>
                <span class="chip chip-muted">
                  <i class="bi bi-clock"></i> {{ now()->format('h:i a') }} {{ __('profile.local_time') }}
                </span>
              </div>

              <div class="profile-rating mt-3 d-flex align-items-center flex-wrap gap-3">
                <span class="stat">
                  <i class="bi bi-check-circle-fill text-success"></i>
                  <b class="mx-1">{{ $user->receivedInvitations()->where('reply','قبول')->count() }}</b>
                  {{ __('profile.accepted') }}
                </span>
                <span class="stat">
                  <i class="bi bi-x-circle-fill text-danger"></i>
                  <b class="mx-1">{{ $user->receivedInvitations()->where('reply','رفض')->count() }}</b>
                  {{ __('profile.rejected') }}
                </span>
              </div>
            </div>

            <div class="profile-image-container ms-3">
              <div class="avatar-wrap">
                <img src="{{ $user->getImageUrlAttribute() }}" alt="Profile" class="profile-avatar">
              </div>
            </div>
          </div>

          <div class="profile-section mt-4">
            <h4 class="section-title">{{ __('profile.about_me') }}</h4>
            <div class="section-content">
              <p class="mb-0">{{ $user->about_me ?? __('profile.no_bio') }}</p>
            </div>
          </div>

          <div class="profile-section">
            <h4 class="section-title">{{ __('profile.skills') }}</h4>
            <div class="section-content">
              @if ($user->skills->count() > 0)
                <ul class="skills-list row row-cols-2 row-cols-md-3 g-3 list-unstyled m-0">
                  @foreach ($user->skills as $skill)
                    <li class="col">
                      <div class="skill-item p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="skill-name">{{ $skill->name }}</span>
                          <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold">
                            {{ $skill->classification->name }}
                          </span>
                        </div>
                        @if ($skill->pivot->description)
                          <p class="skill-description text-muted mb-0 small">{{ $skill->pivot->description }}</p>
                        @endif
                      </div>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="text-muted">{{ __('profile.no_skills') }}</p>
              @endif
            </div>
          </div>

          <div class="profile-section">
            <h4 class="section-title">{{ __('profile.languages') }}</h4>
            <div class="section-content">
              @if ($user->languages->count() > 0)
                <div class="languages-container">
                  @foreach ($user->languages as $language)
                    <div class="language-item d-flex justify-content-between align-items-center py-2 px-3 mb-2">
                      <span class="fw-semibold">{{ $language->name }}</span>
                      <div class="language-level stars">
                        @for ($i=1; $i<=5; $i++)
                          <i class="bi bi-star-fill {{ $i <= $language->pivot->level ? 'text-warning' : 'text-secondary' }}"></i>
                        @endfor
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-muted">{{ __('profile.no_languages') }}</p>
              @endif
            </div>
          </div>

        </div> {{-- /profile-card --}}
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* ===== Tokens بسيطة للصفحة ===== */
:root{
  --pf-bg: #f7f8fb;
  --pf-card: #ffffff;
  --pf-border: #eef1f4;
  --pf-shadow: 0 10px 30px rgba(0,0,0,.06);
  --pf-radius: 16px;
  --pf-radius-sm: 10px;
  --pf-soft: rgba(13,110,253,.08); /* مبني على bs-primary */
}

/* ===== الحاوية العامة ===== */
.profile-page{ background: var(--pf-bg); }

/* ===== البطاقة ===== */
.profile-card{
  background: var(--pf-card);
  border: 1px solid var(--pf-border);
  border-radius: var(--pf-radius);
  box-shadow: var(--pf-shadow);
  padding: 1.5rem;
}

/* ===== الهيدر ===== */
.profile-header{
  padding-bottom: 1rem;
  border-bottom: 1px dashed var(--pf-border);
}

/* ===== الأفاتار مع رينج رقيق ===== */
.avatar-wrap{
  position: relative;
  display: inline-block;
  padding: 4px;
  border-radius: 50%;
  background:
    radial-gradient(120px 120px at 60% 40%, rgba(13,110,253,.18), transparent 60%),
    #fff;
  box-shadow: 0 8px 24px rgba(13,110,253,.12);
}
.profile-avatar{
  width: 9.5rem; height: 9.5rem;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  border: 3px solid #fff;
}

/* ===== نصوص ===== */
.profile-name{ font-weight: 800; color: #222; letter-spacing: .2px; }
.profile-location{ color:#6b7280; }

/* ===== Chips (الحالات الصغيرة) ===== */
.chip{
  display:inline-flex; align-items:center; gap:.4rem;
  padding:.35rem .7rem;
  font-size:.85rem; font-weight:600;
  border-radius: 999px;
  border:1px solid var(--pf-border);
  background:#fff;
}
.chip i{ font-size:.7rem; }
.chip-primary{ color:#0d6efd; background: var(--pf-soft); border-color: transparent; }
.chip-success{ color:#16a34a; background: rgba(22,163,74,.08); border-color: transparent; }
.chip-muted{ color:#6b7280; background:#f3f4f6; }

/* ===== أرقام القبول/الرفض ===== */
.stat{ color:#374151; }
.stat i{ vertical-align: middle; }

/* ===== عناوين الأقسام ===== */
.section-title{
  position: relative;
  font-size: 1.05rem;
  font-weight: 800;
  color: #111827;
  margin: 1.35rem 0 .75rem;
  padding-bottom: .6rem;
  border-bottom: 1px solid var(--pf-border);
}
.section-title::after{
  content:"";
  position:absolute;
  inset-inline-end:0; inset-block-end:-1px;
  width: 84px; height: 3px;
  background: linear-gradient(90deg, rgba(13,110,253,1), rgba(13,110,253,.3));
  border-radius: 10px;
}

/* ===== المهارات ===== */
.skill-item{
  background: #fff;
  border: 1px solid var(--pf-border);
  border-radius: var(--pf-radius-sm);
  transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.skill-item:hover{
  transform: translateY(-2px);
  border-color: rgba(13,110,253,.25);
  box-shadow: 0 8px 18px rgba(13,110,253,.08);
}
.skill-name{ font-weight: 700; color:#1f2937; }

/* توصيف المهارة */
.skill-description{
  color:#6b7280;
}

/* ===== اللغات ===== */
.language-item{
  background: #fff;
  border: 1px dashed var(--pf-border);
  border-radius: var(--pf-radius-sm);
}
.stars i{ margin-inline-start: 2px; font-size: 1rem; }

/* ===== تجاوبية ===== */
@media (max-width: 992px){
  .profile-avatar{ width:8.5rem; height:8.5rem; }
}
@media (max-width: 768px){
  .profile-avatar{ width:7rem; height:7rem; }
  .profile-card{ padding: 1.1rem; }
}
@media (max-width: 576px){
  .profile-avatar{ width:5.5rem; height:5.5rem; }
  .section-title{ font-size: 1rem; }
}

/* دعم ثيمات Bootswatch تلقائياً عبر متغيرات Bootstrap */
.badge.bg-primary-subtle{
  background-color: rgba(var(--bs-primary-rgb), .12) !important;
}
.text-primary{ color: var(--bs-primary) !important; }
</style>
@endpush
