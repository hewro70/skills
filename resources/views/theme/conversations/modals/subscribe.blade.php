{{-- resources/views/theme/conversations/modals/subscribe.blade.php --}}
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
@php
  $modalId = $modalId ?? 'subscribeModal';
  $formId  = $formId  ?? 'subscribeForm';

  // Jordan-only config
  $config  = array_merge([
    'price_local' => '3.5 JOD',      // Inside Jordan (CliQ)
    'cliq_alias'  => 'ZALASKER',     // CliQ alias
  ], $config ?? []);

  $prefill = array_merge([
    'email'        => auth()->user()->email ?? '',
    'account_name' => auth()->user()->name ?? 'your_account',
  ], $prefill ?? []);

  $isRtl = app()->isLocale('ar');
@endphp

@push('styles')
<style>
  #{{ $modalId }} .modal-body{
    max-height: calc(100dvh - 170px);
    overflow: auto;
  }
  #{{ $modalId }} .border.rounded-4.p-3{ padding: .9rem !important; }
  #{{ $modalId }} .mb-3{ margin-bottom: .75rem !important; }
  @media (max-width: 992px){
    #{{ $modalId }} .row.g-4{ row-gap: .75rem !important; }
  }
  @media (max-height: 700px){
    #{{ $modalId }} .modal-header, #{{ $modalId }} .modal-footer{ padding: .5rem .75rem; }
    #{{ $modalId }} .modal-title{ font-size: 1rem; }
  }
  .subscribe-side{ background:#fbfbfd; }
  /* تحسين محاذاة النص عند RTL */
  [dir="rtl"] #{{ $modalId }} .text-start { text-align: right !important; }
  [dir="rtl"] #{{ $modalId }} .text-end   { text-align: left  !important; }
</style>
@endpush

<div class="modal fade mt-3" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <form id="{{ $formId }}" class="modal-content" action="{{ $action ?? route('premium.requests.store') }}">
      @csrf

      <div class="modal-header">
        <div>
          <h5 class="modal-title">{{ __('premium.upgrade_title') }}</h5>
          <div class="text-muted small">{{ __('premium.subtitle') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('premium.close') }}"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          {{-- Left: Receiving info (Jordan / CliQ only) --}}
          <div class="col-md-5">
            <div class="border rounded-4 p-3 h-100 subscribe-side">
              <div class="alert alert-secondary small mb-3">
                {{-- after_sending_note supports :account variable --}}
                {!! __('premium.after_sending_note', ['account' => e($prefill['account_name'])]) !!}
              </div>
              <div class="alert alert-secondary small mb-3">
                {{ __('premium.activate_within') }}
              </div>

              <div class="border rounded p-3 mb-2">
                <div class="small mb-2 fw-semibold">{{ __('premium.inside_jordan') }}</div>
                <div class="small">{{ __('premium.cliq_alias') }}: <b>{{ $config['cliq_alias'] }}</b></div>
                <div class="small">{{ __('premium.amount') }}: <b>{{ $config['price_local'] }}</b></div>
                <div class="small">
                  {{ __('premium.transfer_note') }}:
                  <code>{{ __('premium.premium_ref_prefix', ['account' => $prefill['account_name']]) }}</code>
                </div>
              </div>

              <div class="border rounded p-3 mb-2">
                <a href="https://maharathub.gumroad.com/l/Maharathubpremuim"
                   target="_blank"
                   rel="noopener"
                   class="btn btn-outline-primary w-100">
                  {{ __('premium.pay_with_card') }}
                </a>
              </div>
            </div>
          </div>

          {{-- Right: Transfer form --}}
          <div class="col-md-7">
            <div class="border rounded-4 p-3 h-100" style="padding:.9rem!important">
              <div class="mb-3">
                <label class="form-label">{{ __('premium.sender_wallet_name_label') }}</label>
                <input type="text"
                       class="form-control form-control-sm"
                       name="sender_wallet_name"
                       placeholder="{{ __('premium.sender_wallet_name_ph') }}"
                       required>
              </div>

              {{-- أدخلنا الحساب كمخفي حتى يبقى المرجع يعمل --}}
              <input type="hidden" id="siteAccountName" name="site_account_name" value="{{ $prefill['account_name'] }}">

              <div class="mb-3">
                <label class="form-label">{{ __('premium.txid_label') }}</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="txid"
                       name="txid"
                       placeholder="{{ __('premium.txid_ph') }}"
                       required>
              </div>

              <div class="mb-3">
                <label class="form-label">{{ __('premium.email_label') }}</label>
                <input type="email"
                       class="form-control form-control-sm"
                       id="email"
                       name="email"
                       value="{{ $prefill['email'] }}"
                       placeholder="{{ __('premium.email_ph') }}"
                       required>
              </div>

              <div class="mb-2">
                <label class="form-label">{{ __('premium.note_label') }}</label>
                <textarea class="form-control form-control-sm"
                          name="note"
                          rows="2"
                          placeholder="{{ __('premium.note_ph') }}"></textarea>
              </div>

              <input type="hidden" id="provider" name="provider" value="cliq">
              <input type="hidden" id="reference" name="reference" value="">
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer {{ $isRtl ? 'justify-content-start' : '' }}">
        <button type="submit" id="subscribeSendBtn" class="btn btn-primary">
          {{ __('premium.send_request_btn') }}
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          {{ __('premium.cancel') }}
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
  .subscribe-side{ background:#fbfbfd; }
  @media (max-width: 767.98px){
    .modal .rounded-4{ border-radius: .75rem !important; }
  }
</style>
@endpush

@push('scripts')
<script>
(function(){
  const modal = document.getElementById(@json($modalId));
  if(!modal) return;

  // نمرّر سلاسل مترجمة للـJS عبر data-attributes
  modal.dataset.i18nProcessing = @json(__('premium.processing'));
  modal.dataset.i18nReqOkTitle = @json(__('premium.req_ok_title'));
  modal.dataset.i18nReqOkText  = @json(__('premium.req_ok_text'));
  modal.dataset.i18nErrTitle   = @json(__('premium.error_title'));
  modal.dataset.i18nFailed     = @json(__('premium.failed_generic'));

  modal.addEventListener('submit', async function(e){
    const form = e.target.closest('form');
    if(!form) return;
    e.preventDefault();

    // Set "reference" like: premium - <account>
    const acct = form.querySelector('#siteAccountName')?.value?.trim() || '';
    const ref  = form.querySelector('#reference');
    if (ref) ref.value = @json(__('premium.premium_ref_prefix', ['account' => ':account'])).replace(':account', acct);

    const btn = form.querySelector('#subscribeSendBtn');
    const old = btn?.innerHTML;
    if(btn){
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ' + (modal.dataset.i18nProcessing || 'Processing…');
    }

    try{
      const action = form.getAttribute('action');
      const res = await fetch(action, {
        method: 'POST',
        headers: {
          'X-Requested-With':'XMLHttpRequest',
          'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
          'Accept':'application/json'
        },
        body: new FormData(form)
      });
      let data = {}; try { data = await res.json(); } catch {}
      if(!res.ok || !data.success) throw data?.error || data?.message || (data?.errors && Object.values(data.errors).flat().join('\n')) || (modal.dataset.i18nFailed || 'Failed');

      if(window.Swal) Swal.fire({icon:'success', title: modal.dataset.i18nReqOkTitle, text: modal.dataset.i18nReqOkText, timer:2200, showConfirmButton:false});
      (bootstrap.Modal.getInstance(modal) || new bootstrap.Modal('#'+modal.id)).hide();
      form.reset();
    }catch(err){
      if(window.Swal) Swal.fire({icon:'error', title: modal.dataset.i18nErrTitle, text:String(err||modal.dataset.i18nFailed)});
    }finally{
      if(btn){ btn.disabled = false; btn.innerHTML = old; }
    }
  });
})();
</script>
@endpush
