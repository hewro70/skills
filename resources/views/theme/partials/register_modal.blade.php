@php
  $modalId = $modalId ?? 'registerModal_users';
@endphp

@push('styles')
<style>
  /* فوق كل شي + خلفية ثابتة */
  .modal-backdrop{ z-index:1990 !important; background:#000 !important; }
  .modal-backdrop.show{ opacity:.55 !important; }
  #{{ $modalId }}.modal{ z-index:2000 !important; }

  /* منع أي شفافية/زجاج من ستايلات ثانية */
  #{{ $modalId }} .modal-content{
    background:#fff !important; border:1px solid #e5e7eb !important;
    border-radius:14px !important; box-shadow:0 20px 60px rgba(0,0,0,.25) !important;
    opacity:1 !important;
  }
  #{{ $modalId }} .modal-header{ background:#fff !important; border-bottom:1px solid #f1f5f9 !important; }
  #{{ $modalId }} .modal-body{   background:#fff !important; }
  #{{ $modalId }} .modal-footer{ background:#fff !important; border-top:1px solid #f1f5f9 !important; }

  #{{ $modalId }} .form-label{ font-weight:600; color:#1e293b; }
  #{{ $modalId }} .form-control{
    background:#fff !important; border:1px solid #e5e7eb !important;
    border-radius:10px !important; padding:.6rem .8rem; box-shadow:none !important;
  }
  #{{ $modalId }} .form-control:focus{
    border-color:#94a3b8 !important; outline:0;
    box-shadow:0 0 0 .2rem rgba(13,110,253,.08) !important;
  }
  #{{ $modalId }} .btn-primary{ border-radius:999px; font-weight:700; padding:.6rem 1rem; }
</style>
@endpush

{{-- static backdrop حتى ما يسكّر من الضغط خارج/ESC --}}
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px">
    <form class="modal-content" action="{{ route('register') }}" method="POST" novalidate>
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('auth.register_title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="reg-email" class="form-label">{{ __('auth.email') }}</label>
          <input type="email" class="form-control" id="reg-email" name="email" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
          <label for="reg-password" class="form-label">{{ __('auth.password') }}</label>
          <input type="password" class="form-control" id="reg-password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="reg-password-confirm" class="form-label">{{ __('auth.password_confirm') }}</label>
          <input type="password" class="form-control" id="reg-password-confirm" name="password_confirmation" required>
        </div>

        @if (Route::has('google.login'))
          <div class="text-center">
            <p class="text-muted mb-2">{{ __('auth.or') }}</p>
            <a href="{{ route('google.login') }}" class="btn btn-outline-primary w-100">
              <i class="bi bi-google me-2"></i>{{ __('auth.login_google') }}
            </a>
          </div>
        @endif
      </div>
      <div class="modal-footer d-block">
        <button class="btn btn-primary w-100" type="submit">
          <i class="bi bi-person-plus-fill me-1"></i> {{ __('auth.register_btn') }}
        </button>
        <div class="text-center mt-2">
          <small class="text-muted">
            {{ __('auth.have_account') }}
            <a href="{{ route('login') }}">{{ __('auth.login_link') }}</a>
          </small>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function(){
    // لو اتطبّع جوّا حاوية، انقله للـ body مرّة وحدة
    var m = document.getElementById(@json($modalId));
    if (m && m.parentElement !== document.body) document.body.appendChild(m);

    // دعم Bootstrap 4 إن وُجد
    document.addEventListener('click', function(e){
      var t = e.target.closest('[data-bs-toggle="modal"][data-bs-target]');
      if (!t) return;
      if (!window.bootstrap && window.jQuery && jQuery.fn?.modal) {
        var sel = t.getAttribute('data-bs-target');
        if (sel) jQuery(sel).modal('show');
      }
    }, true);
  });
</script>
@endpush
