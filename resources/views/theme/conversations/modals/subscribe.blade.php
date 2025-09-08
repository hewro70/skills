{{-- resources/views/theme/conversations/modals/subscribe.blade.php --}}
@php
  $modalId = $modalId ?? 'subscribeModal';
  $formId  = $formId  ?? 'subscribeForm';
  $config  = array_merge([
    'price'        => '4.99 USD',
    'wallet_email' => 'wallet@example.com',
    'wallet_name'  => 'Premium Wallet',
    'paypal'       => 'payments@yourapp.com',
  ], $config ?? []);
  $prefill = array_merge([
    'email'        => auth()->user()->email ?? '',
    'account_name' => auth()->user()->name ?? 'your_account',
  ], $prefill ?? []);
@endphp
@push('styles')
<style>
  /* خلّي جسم المودال يلف فقط إذا اضطر */
  #{{ $modalId }} .modal-body{
    max-height: calc(100dvh - 170px); /* يراعي الهيدر والفوتر */
    overflow: auto;
  }
  /* قلل الهوامش العمودية شوي */
  #{{ $modalId }} .border.rounded-4.p-3{ padding: .9rem !important; }
  #{{ $modalId }} .mb-3{ margin-bottom: .75rem !important; }
  /* على الشاشات الصغيرة خليها عمود واحد لتخفيف الطول */
  @media (max-width: 992px){
    #{{ $modalId }} .row.g-4{ row-gap: .75rem !important; }
  }
  /* إذا كان ارتفاع الشاشة قليل، خفّف المسافات */
  @media (max-height: 700px){
    #{{ $modalId }} .modal-header, #{{ $modalId }} .modal-footer{ padding: .5rem .75rem; }
    #{{ $modalId }} .modal-title{ font-size: 1rem; }
  }

  /* تحسين بصري خفيف */
  .subscribe-side{ background:#fbfbfd; }
</style>
@endpush

<div class="modal fade mt-5" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <form id="{{ $formId }}" class="modal-content">
      @csrf

      <div class="modal-header">
        <div>
          <h5 class="modal-title">{{ __('modals.subscribe.title') }}</h5>
          <div class="text-muted small">{{ __('modals.subscribe.subtitle', [], null) ?: 'Choose your payment region then submit the transfer details.' }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          {{-- Left: Region & Receiving info --}}
          <div class="col-md-5">
            <div class="border rounded-4 p-3 h-100 subscribe-side">
              <div class="alert alert-secondary small mb-3">
                {!! __('modals.subscribe.note_html') !!}
              </div>

              <div class="mb-2">
                <label class="form-label fw-semibold">{{ __('modals.subscribe.where') }}</label>
                <div class="d-flex flex-wrap gap-3">
                  <label class="form-check m-0">
                    <input class="form-check-input" type="radio" name="where" value="jordan" required>
                    <span class="form-check-label">{{ __('modals.subscribe.where.jordan') }}</span>
                  </label>
                  <label class="form-check m-0">
                    <input class="form-check-input" type="radio" name="where" value="intl" required>
                    <span class="form-check-label">{{ __('modals.subscribe.where.intl') }}</span>
                  </label>
                </div>
              </div>

              <div id="receivingJordan" class="border rounded p-3 mb-3 d-none">
                <div class="small mb-2 fw-semibold">{{ __('modals.subscribe.rec.jordan.title') }}</div>
                <div class="small">{{ __('modals.subscribe.rec.jordan.wallet_email') }}: <b>{{ $config['wallet_email'] }}</b></div>
                <div class="small">{{ __('modals.subscribe.rec.jordan.wallet_name') }}: <b>{{ $config['wallet_name'] }}</b></div>
                <div class="small">{{ __('modals.subscribe.rec.note_label') }}: <code>premium - {{ $prefill['account_name'] }}</code></div>
                <div class="small">{{ __('modals.subscribe.rec.amount') }}: <b>{{ $config['price'] }}</b></div>
              </div>

              <div id="receivingIntl" class="border rounded p-3 mb-3 d-none">
                <div class="small mb-2 fw-semibold">{{ __('modals.subscribe.rec.intl.title') }}</div>
                <div class="small">PayPal: <b>{{ $config['paypal'] }}</b></div>
                <div class="small">{{ __('modals.subscribe.rec.note_label') }}: <code>premium - {{ $prefill['account_name'] }}</code></div>
                <div class="small">{{ __('modals.subscribe.rec.amount') }}: <b>{{ $config['price'] }}</b></div>
              </div>

              {{--  <div class="small text-muted">
                <i class="bi bi-shield-check me-1"></i>
                {{ __('modals.subscribe.safe_note', [], null) ?: 'We review and activate within 24h.' }}
              </div>  --}}
            </div>
          </div>

          {{-- Right: Transfer form --}}
          <div class="col-md-7">
            <div class="border rounded-4 p-3 h-100" style="padding:.9rem!important">
              <div id="fieldWallet" class="mb-3 d-none">
                <label class="form-label">{{ __('modals.subscribe.form.wallet_name') }}</label>
                <input type="text" class="form-control form-control-sm" name="sender_wallet_name" placeholder="{{ __('modals.subscribe.form.wallet_name') }}">
              </div>

              <div id="fieldPaypal" class="mb-3 d-none">
                <label class="form-label">{{ __('modals.subscribe.form.paypal_email') }}</label>
                <input type="email" class="form-control form-control-sm" name="sender_paypal_email" placeholder="example@domain.com">
              </div>

              <div class="mb-3">
                <label class="form-label">{{ __('modals.subscribe.form.site_account_name') }}</label>
                <input type="text" class="form-control form-control-sm" id="siteAccountName" name="site_account_name" value="{{ $prefill['account_name'] }}" placeholder="{{ __('modals.subscribe.form.site_account_name') }}" required>
              </div>

              <div class="mb-3">
                <label class="form-label">{{ __('modals.subscribe.form.txid') }}</label>
                <input type="text" class="form-control form-control-sm" id="txid" name="txid" placeholder="9F3XZ1..." required>
              </div>

              <div class="mb-3">
                <label class="form-label">{{ __('modals.subscribe.form.email') }}</label>
                <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ $prefill['email'] }}" required>
              </div>

              <div class="mb-2">
                <label class="form-label">{{ __('modals.subscribe.form.note') }}</label>
                <textarea class="form-control form-control-sm" name="note" rows="2" placeholder="{{ __('modals.subscribe.form.note_placeholder') }}"></textarea>
              </div>

              <input type="hidden" id="provider" name="provider" value="">
              <input type="hidden" id="reference" name="reference" value="">
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" id="subscribeSendBtn" class="btn btn-primary">{{ __('modals.subscribe.btn.submit') }}</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
  /* تحسين بسيط للشكل */
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

  function toggleByWhere(where){
    const jBox = modal.querySelector('#receivingJordan');
    const iBox = modal.querySelector('#receivingIntl');
    const wFld = modal.querySelector('#fieldWallet');
    const pFld = modal.querySelector('#fieldPaypal');
    const provider = modal.querySelector('#provider');

    // إظهار معلومات وحقول الجهة المناسبة
    const isJordan = where === 'jordan';
    jBox?.classList.toggle('d-none', !isJordan);
    iBox?.classList.toggle('d-none',  isJordan);
    wFld?.classList.toggle('d-none', !isJordan);
    pFld?.classList.toggle('d-none',  isJordan);

    // required للحقول
    modal.querySelector('[name="sender_wallet_name"]')?.toggleAttribute('required', isJordan);
    modal.querySelector('[name="sender_paypal_email"]')?.toggleAttribute('required', !isJordan);

    if(provider) provider.value = isJordan ? 'local_wallet' : 'paypal';
  }

  modal.addEventListener('shown.bs.modal', ()=>{
    // default select (لو ما كان مختار)
    const checked = modal.querySelector('input[name="where"]:checked');
    if (!checked){
      const def = modal.querySelector('input[name="where"][value="jordan"]');
      if (def){ def.checked = true; toggleByWhere('jordan'); }
    } else {
      toggleByWhere(checked.value);
    }
  });

  modal.addEventListener('change', (e)=>{
    if(e.target && e.target.name === 'where'){
      toggleByWhere(e.target.value);
    }
  });
})();
</script>
@endpush
