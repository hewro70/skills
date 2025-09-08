@extends('theme.master')

@section('title', __('profile.page_title'))

@push('styles')
  {{-- أيقونات --}}

  {{-- Select2 --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  {{-- ستايل خفيف للصفحة (مستقل عن CSS الحالي) --}}
  <style>
    :root{ --nav-h: 64px; }
    .profile-img{ width:110px; height:110px; object-fit:cover; }
    .hover-lift{ transition:.25s; }
    .hover-lift:hover{ transform: translateY(-2px); }
    .main-wrap{ padding-top: var(--nav-h); }
    @media (min-width: 992px){
      .sticky-col{ position: sticky; top: calc(var(--nav-h) + 16px); }
    }
    .skill-badge{ background:#eef3ff; color:#2b4acb; margin: 0 6px 6px 0; }
    .lang-level{ font-size: .85rem; color:#6b7280; }
    .table thead th{ white-space: nowrap; }
  </style>
@endpush

@section('content')

  {{-- Navbar --}}

  <section class="main-wrap">
    <div class="container-fluid">
      <div class="row g-3">
        {{-- Sidebar (Offcanvas on mobile) --}}
        <div class="col-12 d-lg-none">
          <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
            <i class="fas fa-bars me-2"></i>{{ __('profile.menu') }}
          </button>
        </div>

        {{-- Offcanvas --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="offcanvasSidebarLabel">{{ __('profile.sidebar.title') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('common.close') }}"></button>
          </div>
          <div class="offcanvas-body p-0">
            @include('theme.myProfile.sections.sidebar', ['user'=>$user])
          </div>
        </div>

        {{-- Sidebar (Desktop) --}}
        <aside class="col-lg-3 d-none d-lg-block">
          <div class="sticky-col">
            @include('theme.myProfile.sections.sidebar', ['user'=>$user])
          </div>
        </aside>

        {{-- Main Content --}}
        <main class="col-12 col-lg-9" id="mainContent">
          <ul class="nav nav-pills mb-3 flex-wrap" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#pane-info" type="button" role="tab" aria-controls="pane-info" aria-selected="true">
                <i class="fas fa-user me-2"></i>{{ __('profile.sidebar.my_account') }}
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-edit" data-bs-toggle="tab" data-bs-target="#pane-edit" type="button" role="tab" aria-controls="pane-edit" aria-selected="false">
                <i class="fas fa-edit me-2"></i>{{ __('profile.sidebar.edit_profile') }}
              </button>
            </li>
            {{--  <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-qual" data-bs-toggle="tab" data-bs-target="#pane-qual" type="button" role="tab" aria-controls="pane-qual" aria-selected="false">
                <i class="fas fa-graduation-cap me-2"></i>{{ __('profile.sidebar.qualifications') }}
              </button>
            </li>  --}}
          </ul>

          <div class="tab-content">
            {{-- معلومات الحساب --}}
            <div class="tab-pane fade show active" id="pane-info" role="tabpanel" aria-labelledby="tab-info">
              @include('theme.myProfile.sections.profile-info', ['user'=>$user])
            </div>

            {{-- تعديل الحساب + المهارات + اللغات --}}
            <div class="tab-pane fade" id="pane-edit" role="tabpanel" aria-labelledby="tab-edit">
              @include('theme.myProfile.sections.edit', [
                'user'=>$user,
                'skills'=>$skills,
                'languages'=>$languages,
                'countries'=>$countries,
                'classifications'=>$classifications
              ])
            </div>

            {{-- المؤهلات --}}
            <div class="tab-pane fade" id="pane-qual" role="tabpanel" aria-labelledby="tab-qual">
              @include('theme.myProfile.sections.qualifications', ['user'=>$user])
            </div>
          </div>
        </main>
      </div>
    </div>
  </section>

@endsection

@push('scripts')
  {{-- Bootstrap + deps --}}
<script>
document.addEventListener('click', function(e){
  const btn = e.target.closest('.js-open-invite');
  if(!btn) return;

  const userId   = btn.dataset.userId;
  const userName = btn.dataset.userName || '';
  const isPrem   = btn.dataset.isPremiumViewer === '1';
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

  // نظّف الحالة السابقة
  alertEl.className = 'mt-3 d-none';
  alertEl.textContent = '';
  if(msgEl){ msgEl.value = ''; }

  if(isPrem){
    premBox.classList.remove('d-none');
    freeBox.classList.add('d-none');
  }else{
    freeBox.classList.remove('d-none');
    premBox.classList.add('d-none');
    remEl.textContent = remaining;
    limEl.textContent = freeLimit;
  }
});

document.addEventListener('submit', async function(e){
  const form = e.target.closest('#globalInviteForm');
  if(!form) return;

  e.preventDefault();

  const action   = form.dataset.action;
  const btn      = form.querySelector('button[type="submit"]');
  const spinner  = btn.querySelector('.spinner-border');
  const sendTxt  = btn.querySelector('.send-text');
  const alertBox = document.getElementById('globalInviteAlert');

  const fd = new FormData(form);

  btn.disabled = true; spinner.classList.remove('d-none'); sendTxt.textContent = 'جارٍ الإرسال…';

  try{
    const res  = await fetch(action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: fd
    });
    const data = await res.json();

    alertBox.classList.remove('d-none','alert-success','alert-warning','alert-danger');
    alertBox.classList.add('alert','mt-3', res.ok ? 'alert-success' : (res.status===422 ? 'alert-warning' : 'alert-danger'));
    alertBox.textContent = data.message || (res.ok ? 'تم إرسال الدعوة.' : 'تعذّر إرسال الدعوة.');

    if(res.ok){
      setTimeout(()=>{
        const modalEl = document.getElementById('globalInviteModal');
        const m = bootstrap.Modal.getInstance(modalEl);
        m && m.hide();
      }, 900);
    }
  }catch(_){
    alertBox.classList.remove('d-none','alert-success','alert-warning');
    alertBox.classList.add('alert','alert-danger','mt-3');
    alertBox.textContent = 'حدث خطأ غير متوقع.';
  }finally{
    btn.disabled = false; spinner.classList.add('d-none'); sendTxt.textContent = 'إرسال';
  }
});
</script>

  {{-- Select2 --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  {{-- Axios + SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('theme.myProfile.sections.scripts', [
    'user' => $user,
    'skills' => $skills,
    'languages' => $languages,
    'countries' => $countries,
    'classifications' => $classifications
  ])
@endpush
