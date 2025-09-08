{{-- resources/views/theme/conversations/modals/subscribe.blade.php --}}
@php
  $modalId = $modalId ?? 'subscribeModal';
  $formId  = $formId  ?? 'subscribeForm';

  // Client-required payment config (can be overridden via $config)
  $config  = array_merge([
    'price_local' => '3.5 JOD',                         // Inside Jordan
    'price_intl'  => '4.99 USD',                        // Outside Jordan
    'cliq_alias'  => 'ZALASKER',                        // CliQ alias
    'paypal_link' => 'https://paypal.me/MaharatHub',    // PayPal link
  ], $config ?? []);

  $prefill = array_merge([
    'email'        => auth()->user()->email ?? '',
    'account_name' => auth()->user()->name ?? 'your_account',
  ], $prefill ?? []);
@endphp

@push('styles')
<style>
  /* Keep modal body scroll only if needed */
  #{{ $modalId }} .modal-body{
    max-height: calc(100dvh - 170px);
    overflow: auto;
  }
  /* Tighten vertical rhythm a bit */
  #{{ $modalId }} .border.rounded-4.p-3{ padding: .9rem !important; }
  #{{ $modalId }} .mb-3{ margin-bottom: .75rem !important; }

  /* On small screens, reduce gaps */
  @media (max-width: 992px){
    #{{ $modalId }} .row.g-4{ row-gap: .75rem !important; }
  }
  /* If viewport height is small, compact header/footer */
  @media (max-height: 700px){
    #{{ $modalId }} .modal-header, #{{ $modalId }} .modal-footer{ padding: .5rem .75rem; }
    #{{ $modalId }} .modal-title{ font-size: 1rem; }
  }

  /* Light side background */
  .subscribe-side{ background:#fbfbfd; }
</style>
@endpush

<div class="modal fade mt-5" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <form id="{{ $formId }}" class="modal-content">
      @csrf

      <div class="modal-header">
        <div>
          <h5 class="modal-title">Upgrade to Premium</h5>
          <div class="text-muted small">Select your region, then submit your transfer details.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          {{-- Left: Region & Receiving info --}}
          <div class="col-md-5">
            <div class="border rounded-4 p-3 h-100 subscribe-side">
              <div class="alert alert-secondary small mb-3">
                Please complete the payment, then fill the form. Use the note format: <code>premium - {{ $prefill['account_name'] }}</code>
              </div>

              <div class="mb-2">
                <label class="form-label fw-semibold">Your location</label>
                <div class="d-flex flex-wrap gap-3">
                  <label class="form-check m-0">
                    <input class="form-check-input" type="radio" name="where" value="jordan" required>
                    <span class="form-check-label">Inside Jordan</span>
                  </label>
                  <label class="form-check m-0">
                    <input class="form-check-input" type="radio" name="where" value="intl" required>
                    <span class="form-check-label">Outside Jordan</span>
                  </label>
                </div>
              </div>

              <div id="receivingJordan" class="border rounded p-3 mb-3 d-none">
                <div class="small mb-2 fw-semibold">Receiving details (Jordan / CliQ)</div>
                <div class="small">CliQ Alias: <b>{{ $config['cliq_alias'] }}</b></div>
                <div class="small">Amount: <b>{{ $config['price_local'] }}</b></div>
                <div class="small">Transfer note: <code>premium - {{ $prefill['account_name'] }}</code></div>
              </div>

              <div id="receivingIntl" class="border rounded p-3 mb-3 d-none">
                <div class="small mb-2 fw-semibold">Receiving details (Outside Jordan / PayPal)</div>
                <div class="small">PayPal: <a href="{{ $config['paypal_link'] }}" target="_blank" rel="noopener" class="text-decoration-underline">{{ $config['paypal_link'] }}</a></div>
                <div class="small">Amount: <b>{{ $config['price_intl'] }}</b></div>
                <div class="small">Transfer note: <code>premium - {{ $prefill['account_name'] }}</code></div>
              </div>
            </div>
          </div>

          {{-- Right: Transfer form --}}
          <div class="col-md-7">
            <div class="border rounded-4 p-3 h-100" style="padding:.9rem!important">
              <div id="fieldWallet" class="mb-3 d-none">
                <label class="form-label">Your CliQ name (sender)</label>
                <input type="text" class="form-control form-control-sm" name="sender_wallet_name" placeholder="Your name on CliQ">
              </div>

              <div id="fieldPaypal" class="mb-3 d-none">
                <label class="form-label">Your PayPal email (sender)</label>
                <input type="email" class="form-control form-control-sm" name="sender_paypal_email" placeholder="example@domain.com">
              </div>

              <div class="mb-3">
                <label class="form-label">Your site account name</label>
                <input type="text" class="form-control form-control-sm" id="siteAccountName" name="site_account_name" value="{{ $prefill['account_name'] }}" placeholder="your_account" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Transaction ID</label>
                <input type="text" class="form-control form-control-sm" id="txid" name="txid" placeholder="9F3XZ1..." required>
              </div>

              <div class="mb-3">
                <label class="form-label">Notification email</label>
                <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ $prefill['email'] }}" required>
              </div>

              <div class="mb-2">
                <label class="form-label">Note (optional)</label>
                <textarea class="form-control form-control-sm" name="note" rows="2" placeholder="Any transfer details..."></textarea>
              </div>

              <input type="hidden" id="provider" name="provider" value="">
              <input type="hidden" id="reference" name="reference" value="">
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" id="subscribeSendBtn" class="btn btn-primary">Transferred — Send request</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
  /* minor visual enhancement */
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

    const isJordan = where === 'jordan';
    jBox?.classList.toggle('d-none', !isJordan);
    iBox?.classList.toggle('d-none',  isJordan);
    wFld?.classList.toggle('d-none', !isJordan);
    pFld?.classList.toggle('d-none',  isJordan);

    // toggle required
    const wInput = modal.querySelector('[name="sender_wallet_name"]');
    const pInput = modal.querySelector('[name="sender_paypal_email"]');
    if (wInput) { isJordan ? wInput.setAttribute('required', 'required') : wInput.removeAttribute('required'); }
    if (pInput) { !isJordan ? pInput.setAttribute('required', 'required') : pInput.removeAttribute('required'); }

    if(provider) provider.value = isJordan ? 'cliq' : 'paypal';
  }

  modal.addEventListener('shown.bs.modal', ()=>{
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
