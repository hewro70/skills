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
               {{ $user->location_text }}
              </p>

              <div class="profile-meta d-flex align-items-center flex-wrap gap-3">
                {{--  <span class="chip chip-success">
                  <i class="bi bi-circle-fill"></i> {{ __('profile.available_now') }}
                </span>  --}}
                <span class="chip chip-primary">
                  <i class="bi bi-star-fill"></i> {{ __('profile.top_rated') }}
                </span>
                <span class="chip chip-muted">
                 @if($user->is_mentor)
  
    <i class="bi bi-mortarboard-fill me-1"></i> {{ __('badges.mentor') ?? 'Mentor' }}

@endif
                </span>
                              {{-- داخل .profile-header / جنب الشيبس أو تحتها --}}
@auth
  <button type="button"
          class="btn btn-sm btn-outline-primary js-open-invite"
          data-user-id="{{ $user->id }}"
          data-user-name="{{ $user->fullName() }}">
    <i class="bi bi-send"></i> {{ __('talent.invite') ?? 'Invite' }}
  </button>
@else
  <button type="button"
          class="btn btn-sm btn-outline-primary"
          data-bs-toggle="modal"
          data-bs-target="#registerModal_users">
    <i class="bi bi-person-plus"></i> {{ __('auth.register_btn') ?? 'Register' }}
  </button>
@endauth
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

  @php
    // القيمة الخام (قد تكون رقم أو نص)
    $raw = $language->pivot->level ?? null;
    $key = null; // نحولها لمفتاح 1..5

    if (is_numeric($raw)) {
        $num = (int) $raw;
        if ($num >= 1 && $num <= 5)       $key = $num;        // 1..5
        elseif ($num >= 0 && $num <= 4)   $key = $num + 1;    // 0..4 -> 1..5
    } else {
        $val = trim(mb_strtolower((string) $raw));
        // دعم صيغ شائعة (عربي/إنجليزي/CEFR)
        $mapStr = [
          'a1'=>1, 'beginner'=>1, 'مبتدئ'=>1,
          'a2'=>2, 'elementary'=>2, 'أساسي'=>2,
          'b1'=>3, 'intermediate'=>3, 'متوسط'=>3,
          'b2'=>4, 'upper-intermediate'=>4, 'متقدم'=>4,
          'c1'=>5, 'c2'=>5, 'fluent'=>5, 'native'=>5, 'طليق'=>5, 'متحدث أصلي'=>5,
        ];
        if (isset($mapStr[$val])) $key = $mapStr[$val];
    }

    // التسميات والألوان
    $labels = [
      1 => ['label'=>'مبتدئ','abbr'=>'A1','class'=>'lv-beginner'],
      2 => ['label'=>'أساسي','abbr'=>'A2','class'=>'lv-elementary'],
      3 => ['label'=>'متوسط','abbr'=>'B1','class'=>'lv-intermediate'],
      4 => ['label'=>'متقدم','abbr'=>'B2','class'=>'lv-advanced'],
      5 => ['label'=>'طليق','abbr'=>'C1/C2','class'=>'lv-native'],
    ];

    $meta = $labels[$key] ?? ['label'=>'غير محدد','abbr'=>'','class'=>'lv-unknown'];
  @endphp
  

  <div class="language-level">
    <span class="level-chip {{ $meta['class'] }}">
      {{ $meta['label'] }} @if($meta['abbr']) <small class="opacity-75 mx-1">({{ $meta['abbr'] }})</small> @endif
    </span>
  </div>
</div>

                  @endforeach
                </div>
              @else
                <p class="text-muted">{{ __('profile.no_languages') }}</p>
              @endif
            </div>
          </div>
{{-- Reviews (يشتغل مباشرة من علاقة $user->receivedReviews) --}}
<div class="profile-section" id="reviews">
  <h4 class="section-title">التقييمات</h4>

  @php
    // لاحظ: العمود عندك اسمه ratings
    $avgVal = $user->receivedReviews()->avg('ratings');
    $avg    = $avgVal !== null ? round((float)$avgVal, 1) : 0.0;

    $count  = $user->receivedReviews()->count();

    // آخر 5 تقييمات (لا نحدد أعمدة لتفادي Unknown column)
    $latest = $user->receivedReviews()
                  ->orderByDesc('created_at')
                  ->limit(5)
                  ->get();
  @endphp

  <div class="d-flex align-items-center gap-3 mb-3">
    <div class="rating-summary d-flex align-items-center">
      @for ($i=1; $i<=5; $i++)
        <i class="bi bi-star-fill {{ $i <= floor($avg) ? 'text-warning' : 'text-secondary' }}"></i>
      @endfor
      <span class="ms-2 fw-bold">{{ number_format($avg, 1) }}</span>
      <span class="text-muted ms-1">/ 5</span>
    </div>
    <span class="text-muted">({{ $count }} تقييم)</span>
  </div>

  @if($latest->isNotEmpty())
    <ul class="list-unstyled m-0">
      @foreach ($latest as $row)
        @php
          $rating = (int)($row->ratings ?? 0);     // ← العمود الصحيح
          $text   = (string)($row->comment ?? ''); // عندك comment في الموديل
          // تنسيق التاريخ بأمان سواء كان Carbon أو نص
          $dateLabel = '';
          if ($row->created_at instanceof \Illuminate\Support\Carbon) {
            $dateLabel = $row->created_at->format('Y-m-d');
          } elseif (!empty($row->created_at)) {
            $dateLabel = substr((string)$row->created_at, 0, 10);
          }
        @endphp

        <li class="review-item p-3 mb-2">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="review-stars">
              @for ($i=1; $i<=5; $i++)
                <i class="bi bi-star-fill {{ $i <= $rating ? 'text-warning' : 'text-secondary' }}"></i>
              @endfor
            </div>
            @if($dateLabel)
              <small class="text-muted">{{ $dateLabel }}</small>
            @endif
          </div>

          @if($text !== '')
            <p class="mb-0 text-muted">{{ $text }}</p>
          @endif
        </li>
      @endforeach
    </ul>
  @else
    <p class="text-muted m-0">لا يوجد تقييمات بعد.</p>
  @endif
</div>

        </div> {{-- /profile-card --}}
        @auth
  <div class="modal fade mt-5" id="globalInviteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="globalInviteForm" data-action="{{ route('invitations.send') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="globalInviteTitle">{{ __('invitations.title') ?? 'Send Invitation' }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('invitations.cancel') ?? 'Close' }}"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="destination_user_id" id="inviteUserId">
          <div id="inviteFreeBox" class="d-none">
            <div class="alert alert-info d-flex align-items-center gap-2">
              <i class="bi bi-info-circle"></i>
              <div>{{ __('invitations.free.remaining_html', ['remaining'=>0,'limit'=>5]) ?? 'Free invites info' }}</div>
            </div>
          </div>
          <div id="invitePremiumBox" class="d-none">
            <label class="form-label">{{ __('invitations.premium.message_label') ?? 'Message' }}</label>
            <textarea name="message" id="inviteMessage" class="form-control" rows="4" maxlength="1000"
                      placeholder="{{ __('invitations.premium.message_label') ?? 'Write a message...' }}"></textarea>
            <div class="form-text">{{ __('invitations.premium.message_help') ?? '' }}</div>
          </div>
          <div class="mt-3 d-none" id="globalInviteAlert"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('invitations.cancel') ?? 'Cancel' }}</button>
          <button type="submit" class="btn btn-primary">
            <span class="send-text">{{ __('invitations.send') ?? 'Send' }}</span>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
@endauth

      </div>
    </div>
  </div>
</div>

<script>
(function(){
  // فتح المودال من زر البروفايل
  document.addEventListener('click', async function(e){
    const btn = e.target.closest('.js-open-invite');
    if(!btn) return;

    // قبل أي شيء: افحص الأهلية
    try{
      const res  = await fetch(`{{ route('invitations.check') }}`, {
        method: 'GET', headers:{ 'X-Requested-With':'XMLHttpRequest' }, credentials:'same-origin'
      });
      const data = await res.json();
      if (data.status === 'unauthenticated') {
        // زائر: افتح مودال التسجيل إن وجد وإلا وجّهه للتسجيل
        const m = document.getElementById('registerModal_users');
        if (m && window.bootstrap) new bootstrap.Modal(m).show();
        else window.location.href = `{{ route('register') }}`;
        return;
      }
      if (data.status === 'incomplete') {
        // غير مكتمل
        Swal.fire({
          icon:'info', title:'الملف الشخصي غير مكتمل',
          html:`يجب إكمال ملفك الشخصي 100% قبل إرسال الدعوات.<br>
                <small>نسبة الاكتمال الحالية: <b>${data.completion_percentage ?? 0}%</b></small><br>
                <a href="{{ route('myProfile') }}" class="btn btn-primary mt-2">إكمال الملف الآن</a>`
        });
        return;
      }
      if (data.status === 'limit_reached') {
        Swal.fire({ icon:'info', title:'انتهى الحد المتاح', text: data.message || 'لقد استهلكت جميع الدعوات المتاحة.' });
        return;
      }
      // مقبول: جهّز المودال
      const userId   = btn.dataset.userId;
      const userName = btn.dataset.userName || '';

      const titleEl  = document.getElementById('globalInviteTitle');
      const userIdEl = document.getElementById('inviteUserId');
      const freeBox  = document.getElementById('inviteFreeBox');
      const premBox  = document.getElementById('invitePremiumBox');
      const msgEl    = document.getElementById('inviteMessage');
      const alertEl  = document.getElementById('globalInviteAlert');

      titleEl.textContent = 'إرسال دعوة إلى ' + userName;
      userIdEl.value = userId;
      alertEl.className = 'd-none'; alertEl.textContent = '';
      if (msgEl) msgEl.value = '';

      // إظهار صندوق مناسب (لو عندك منطق تمييز بريميوم)
      premBox && premBox.classList.remove('d-none');
      freeBox && freeBox.classList.add('d-none');

      new bootstrap.Modal(document.getElementById('globalInviteModal')).show();
    }catch(_){
      Swal.fire({ icon:'error', title:'تعذّر التحقق', text:'حاول لاحقًا.' });
    }
  });

  // إرسال الدعوة من المودال
  document.addEventListener('submit', async function(e){
    const form = e.target.closest('#globalInviteForm');
    if(!form) return;
    e.preventDefault();

    if (form.dataset.busy === '1') return;
    form.dataset.busy = '1';

    const btn      = form.querySelector('button[type="submit"]');
    const spinner  = btn.querySelector('.spinner-border');
    const sendTxt  = btn.querySelector('.send-text');
    const alertBox = document.getElementById('globalInviteAlert');
    const fd       = new FormData(form);

    btn.disabled = true; spinner.classList.remove('d-none'); sendTxt.textContent = 'جارٍ الإرسال…';

    try{
      const res  = await fetch(form.dataset.action, {
        method:'POST', headers:{ 'X-CSRF-TOKEN': `{{ csrf_token() }}` }, body:fd
      });
      const data = await res.json();

      alertBox.classList.remove('d-none','alert-success','alert-warning','alert-danger','mt-3');
      alertBox.classList.add('alert','mt-3', res.ok ? 'alert-success' : (res.status===422 ? 'alert-warning' : 'alert-danger'));
      alertBox.textContent = data.message || (res.ok ? 'تم إرسال الدعوة.' : 'تعذّر إرسال الدعوة.');

      if(res.ok){
        setTimeout(()=>{
          const m = bootstrap.Modal.getInstance(document.getElementById('globalInviteModal'));
          m && m.hide();
        }, 900);
      }
    }catch(_){
      alertBox.classList.remove('d-none','alert-success','alert-warning');
      alertBox.classList.add('alert','alert-danger','mt-3');
      alertBox.textContent = 'حدث خطأ غير متوقع.';
    }finally{
      btn.disabled = false; spinner.classList.add('d-none'); sendTxt.textContent = `{{ __('invitations.send') ?? 'Send' }}`;
      form.dataset.busy = '0';
    }
  });
})();
</script>

@endsection
<style>
  .review-item{
    background:#fff;
    border:1px solid var(--pf-border);
    border-radius: var(--pf-radius-sm);
  }
  .review-stars i{ font-size:.95rem; }
  .rating-summary i{ font-size:1.05rem; }
</style>

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
