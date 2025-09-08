{{-- talent list --}}
@push('styles')
<style>
  :root{
    --tl-border:#e5e7eb;
    --tl-soft:#f1f5f9;
    --tl-text:#1e293b;
    --tl-muted:#475569;
  }
  .talent-card{
    background:#fff; border:1px solid var(--tl-border); border-radius:12px;
    padding:16px; margin-bottom:16px;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
    transition:transform .15s ease, box-shadow .15s ease;
  }
  .talent-card:hover{ transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.08); }
  .talent-header{ display:flex; align-items:center; gap:12px; }
  .talent-avatar{ width:64px; height:64px; border-radius:50%; object-fit:cover; border:2px solid var(--tl-soft); }
  .talent-info h4{ margin:0; font-size:1.1rem; font-weight:800; color:var(--tl-text); }
  .talent-langs{ margin:0; font-size:.9rem; color:var(--tl-muted); }
  .talent-location{ font-size:.85rem; color:#64748b; }
  .talent-stats{ display:flex; flex-wrap:wrap; gap:1rem; margin-top:.75rem; font-size:.9rem; color:#334155; }
  .talent-stats span i{ margin-inline-end:4px; }
  .talent-description{ margin-top:.75rem; font-size:.95rem; color:var(--tl-muted); line-height:1.6; }
  .talent-skills{ margin-top:.75rem; display:flex; flex-wrap:wrap; gap:.4rem; }
  .skill-tag{
    background:var(--tl-soft); color:var(--bs-primary); font-size:.8rem; font-weight:700;
    border-radius:999px; padding:.35rem .7rem; display:inline-flex; align-items:center; gap:.25rem;
  }
  .talent-actions{ margin-top:1rem; display:flex; gap:.5rem; flex-wrap:wrap; }
</style>
@endpush

@php
  // جهّز بيانات الاشتراك/العدّاد مرة واحدة
  $viewer = auth()->user();
  $isPremiumViewer = $viewer
    ? (method_exists($viewer, 'hasActiveSubscription') ? $viewer->hasActiveSubscription() : ($viewer->is_premium ?? false))
    : false;

  // يفضل تمرير $sentInvitesThisMonth من الكنترولر
  $sentInvitesThisMonth = $sentInvitesThisMonth ?? 0;
  $freeLimit = 5;
  $remaining = max(0, $freeLimit - (int)$sentInvitesThisMonth);
@endphp

@forelse ($users as $user)
  @php
    $rating       = $user->avg_rating ?? null;
    $ratingCount  = $user->ratings_count ?? null;
    $responseRate = $user->response_rate ?? null;
  @endphp

  <div class="talent-card">
    {{-- Header --}}
    <div class="talent-header">
      <img src="{{ $user->getImageUrlAttribute()}}" alt="Talent" class="talent-avatar">
      <div class="talent-info">
        <h4 class="talent-name">{{ $user->fullName() }}</h4>

        {{-- لغات --}}
        <p class="talent-langs">
          @if($user->languages->count())
            @foreach ($user->languages as $language)
              <span>
                {{ $language->name }}
                ({{ $language->pivot->level ?? '—' }})
              </span>@if(!$loop->last) | @endif
            @endforeach
          @else
            <span class="text-muted">{{ __('talent.no_languages') }}</span>
          @endif
        </p>

        {{-- دولة --}}
        <span class="talent-location">
          <i class="bi bi-geo-alt"></i>
          {{ $user->location_text }}
        </span>
      </div>
    </div>

    {{-- إحصائيات --}}
    <div class="talent-stats">
      @if($rating)
        <span>
          <i class="bi bi-star-fill text-warning"></i>
          {{ number_format($rating, 1) }}
          @if($ratingCount) ({{ $ratingCount }} {{ __('talent.reviews') }}) @endif
        </span>
      @endif

      @if($responseRate)
        <span>
          <i class="bi bi-check-circle-fill text-success"></i>
          {{ $responseRate }}% {{ __('talent.response_rate') }}
        </span>
      @endif
    </div>

    {{-- الوصف --}}
    <div class="talent-description">
      <p>{{ $user->about_me ?? __('talent.no_bio') }}</p>
    </div>

    {{-- المهارات --}}
    <div class="talent-skills">
      @forelse ($user->skills as $skill)
        <span class="skill-tag"><i class="bi bi-lightning-fill"></i> {{ $skill->name }}</span>
      @empty
        <span class="text-muted">{{ __('talent.no_skills') }}</span>
      @endforelse
    </div>

    {{-- الأكشن --}}
    <div class="talent-actions">
      <a href="{{ route('theme.profile.show', $user) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-person-badge"></i> {{ __('talent.view_profile') }}
      </a>

      @auth
        <button type="button"
                class="btn btn-sm btn-outline-primary js-open-invite"
                data-user-id="{{ $user->id }}"
                data-user-name="{{ $user->fullName() }}"
                data-is-premium-viewer="{{ $isPremiumViewer ? 1 : 0 }}"
                data-remaining="{{ $remaining }}"
                data-free-limit="{{ $freeLimit }}"
                data-bs-toggle="modal"
                data-bs-target="#globalInviteModal">
          <i class="bi bi-send"></i> {{ __('talent.invite') }}
        </button>
      @endauth

      @guest
        <button type="button"
                class="btn btn-sm btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#registerModal_users">
          <i class="bi bi-person-plus"></i> {{ __('auth.register_btn') }}
        </button>
      @endguest
    </div>
  </div>
@empty
  <div class="col-12 text-center py-5">
    <h4 class="text-muted">{{ __('talent.no_results') }}</h4>
  </div>
@endforelse

<script>
(function(){
  // فتح المودال وتعبئته
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.js-open-invite');
    if(!btn) return;

    const userId    = btn.dataset.userId;
    const userName  = btn.dataset.userName || '';
    const isPrem    = btn.dataset.isPremiumViewer === '1';
    const remaining = btn.dataset.remaining || '0';
    const freeLimit = btn.dataset.freeLimit || '5';

    const titleEl  = document.getElementById('globalInviteTitle');
    const userIdEl = document.getElementById('inviteUserId');
    const freeBox  = document.getElementById('inviteFreeBox');
    const premBox  = document.getElementById('invitePremiumBox');
    const msgEl    = document.getElementById('inviteMessage');
    const remEl    = document.getElementById('inviteRemaining');
    const limEl    = document.getElementById('inviteFreeLimit');
    const alertEl  = document.getElementById('globalInviteAlert');

    titleEl.textContent = 'إرسال دعوة إلى ' + userName;
    userIdEl.value = userId;

    alertEl.className = 'd-none';
    alertEl.textContent = '';
    if(msgEl){ msgEl.value = ''; }

    if(isPrem){
      premBox.classList.remove('d-none');
      freeBox.classList.add('d-none');
    }else{
      freeBox.classList.remove('d-none');
      premBox.classList.add('d-none');
      if(remEl) remEl.textContent = remaining;
      if(limEl) limEl.textContent = freeLimit;
    }
  });

  // إرسال المودال مع فحص الأهلية
  document.addEventListener('submit', async function(e){
    const form = e.target.closest('#globalInviteForm');
    if(!form) return;

    e.preventDefault();
    e.stopPropagation();
    if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();

    // منع الضغط المزدوج
    if (form.dataset.busy === '1') return;
    form.dataset.busy = '1';

    // 1) فحص الأهلية (تسجيل الدخول + 100% اكتمال + حدود مجانية)
    try{
      const chk = await fetch(`{{ route('invitations.check') }}`, {
        method: 'GET',
        headers: { 'X-Requested-With':'XMLHttpRequest' },
        credentials: 'same-origin'
      });
      const data = await chk.json();

      if (data.status === 'unauthenticated') {
        await Swal.fire({ icon:'warning', title:'غير مسجّل', text:'يرجى تسجيل الدخول أولاً.' });
        window.location.href = `{{ route('login') }}`;
        form.dataset.busy = '0';
        return;
      }
      if (data.status === 'incomplete') {
        await Swal.fire({
          icon:'info',
          title:'الملف الشخصي غير مكتمل',
          html:`يجب إكمال ملفك الشخصي 100% قبل إرسال الدعوات.<br>
                <small>نسبة الاكتمال الحالية: <b>${data.completion_percentage ?? 0}%</b></small><br>
                <a href="{{ route('myProfile') }}" class="btn btn-primary mt-2">إكمال الملف الآن</a>`
        });
        form.dataset.busy = '0';
        return;
      }
      if (data.status === 'limit_reached') {
        await Swal.fire({ icon:'info', title:'انتهى الحد المجاني', text: data.message || 'لقد استهلكت جميع الدعوات المتاحة.' });
        form.dataset.busy = '0';
        return;
      }
      // (status === 'ok') → كمّل إرسال
    } catch(_){
      await Swal.fire({ icon:'error', title:'تعذّر التحقق', text:'حاول لاحقًا.' });
      form.dataset.busy = '0';
      return;
    }

    // 2) الإرسال الفعلي
    const btn      = form.querySelector('button[type="submit"]');
    const spinner  = btn.querySelector('.spinner-border');
    const sendTxt  = btn.querySelector('.send-text');
    const alertBox = document.getElementById('globalInviteAlert');
    const fd       = new FormData(form); // يحوي message/description

    btn.disabled = true; spinner.classList.remove('d-none'); sendTxt.textContent = 'جارٍ الإرسال…';

    try{
      const res  = await fetch(form.dataset.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': `{{ csrf_token() }}` },
        body: fd
      });
      const resp = await res.json();

      alertBox.classList.remove('d-none','alert-success','alert-warning','alert-danger','mt-3');
      alertBox.classList.add('alert','mt-3', res.ok ? 'alert-success' : (res.status===422 ? 'alert-warning' : 'alert-danger'));
      alertBox.textContent = resp.message || (res.ok ? 'تم إرسال الدعوة.' : 'تعذّر إرسال الدعوة.');

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
      btn.disabled = false; spinner.classList.add('d-none'); sendTxt.textContent = `{{ __('invitations.send') }}`;
      form.dataset.busy = '0';
    }
  }, true); // capture لتسبق أي لسنرز قديمة
})();
</script>
