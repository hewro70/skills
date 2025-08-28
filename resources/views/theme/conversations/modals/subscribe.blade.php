{{-- resources/views/theme/conversations/modals/subscribe.blade.php --}}
@php
  $modalId = $modalId ?? 'subscribeModal';
  $formId  = $formId  ?? 'subscribeForm';
  $config  = array_merge([
    'price'        => '10 USD',
    'wallet_email' => 'wallet@example.com',
    'wallet_name'  => 'Premium Wallet',
    'paypal'       => 'payments@yourapp.com',
  ], $config ?? []);
  $prefill = array_merge([
    'email'        => auth()->user()->email ?? '',
    'account_name' => auth()->user()->name ?? 'your_account',
  ], $prefill ?? []);
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="{{ $formId }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('modals.subscribe.title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>

      <div class="modal-body">
        <div class="alert alert-secondary small mb-3">
          {!! __('modals.subscribe.note_html') !!}
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('modals.subscribe.where') }}</label>
          <div class="d-flex gap-3">
            <label class="form-check">
              <input class="form-check-input" type="radio" name="where" value="jordan" required>
              <span class="form-check-label">{{ __('modals.subscribe.where.jordan') }}</span>
            </label>
            <label class="form-check">
              <input class="form-check-input" type="radio" name="where" value="intl" required>
              <span class="form-check-label">{{ __('modals.subscribe.where.intl') }}</span>
            </label>
          </div>
        </div>

        <div id="receivingJordan" class="border rounded p-2 mb-2 d-none">
          <div class="small mb-1"><b>{{ __('modals.subscribe.rec.jordan.title') }}</b></div>
          <div class="small">{{ __('modals.subscribe.rec.jordan.wallet_email') }}: <b>{{ $config['wallet_email'] }}</b></div>
          <div class="small">{{ __('modals.subscribe.rec.jordan.wallet_name') }}: <b>{{ $config['wallet_name'] }}</b></div>
          <div class="small">{{ __('modals.subscribe.rec.note_label') }}: <code>premium - {{ $prefill['account_name'] }}</code></div>
          <div class="small">{{ __('modals.subscribe.rec.amount') }}: <b>{{ $config['price'] }}</b></div>
        </div>

        <div id="receivingIntl" class="border rounded p-2 mb-2 d-none">
          <div class="small mb-1"><b>{{ __('modals.subscribe.rec.intl.title') }}</b></div>
          <div class="small">PayPal: <b>{{ $config['paypal'] }}</b></div>
          <div class="small">{{ __('modals.subscribe.rec.note_label') }}: <code>premium - {{ $prefill['account_name'] }}</code></div>
          <div class="small">{{ __('modals.subscribe.rec.amount') }}: <b>{{ $config['price'] }}</b></div>
        </div>

        <div id="fieldWallet" class="mb-3 d-none">
          <label class="form-label">{{ __('modals.subscribe.form.wallet_name') }}</label>
          <input type="text" class="form-control" name="sender_wallet_name" placeholder="{{ __('modals.subscribe.form.wallet_name') }}">
        </div>

        <div id="fieldPaypal" class="mb-3 d-none">
          <label class="form-label">{{ __('modals.subscribe.form.paypal_email') }}</label>
          <input type="email" class="form-control" name="sender_paypal_email" placeholder="example@domain.com">
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('modals.subscribe.form.site_account_name') }}</label>
          <input type="text" class="form-control" id="siteAccountName" name="site_account_name" value="{{ $prefill['account_name'] }}" placeholder="{{ __('modals.subscribe.form.site_account_name') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('modals.subscribe.form.txid') }}</label>
          <input type="text" class="form-control" id="txid" name="txid" placeholder="9F3XZ1..." required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('modals.subscribe.form.email') }}</label>
          <input type="email" class="form-control" id="email" name="email" value="{{ $prefill['email'] }}" required>
        </div>

        <div class="mb-2">
          <label class="form-label">{{ __('modals.subscribe.form.note') }}</label>
          <textarea class="form-control" name="note" rows="2" placeholder="{{ __('modals.subscribe.form.note_placeholder') }}"></textarea>
        </div>

        <input type="hidden" id="provider" name="provider" value="">
        <input type="hidden" id="reference" name="reference" value="">
      </div>

      <div class="modal-footer">
        <button type="submit" id="subscribeSendBtn" class="btn btn-primary">{{ __('modals.subscribe.btn.submit') }}</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
      </div>
    </form>
  </div>
</div>
