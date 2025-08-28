{{-- resources/views/theme/conversations/modals/premium.blade.php --}}
<div class="modal fade" id="{{ $modalId ?? 'premiumModal' }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning-subtle">
        <h5 class="modal-title">{{ __('modals.premium.title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">{{ __('modals.premium.body1') }}</p>
        <ul class="mb-3 ps-3">
          <li>{{ __('modals.premium.list.unlimited') }}</li>
          <li>{{ __('modals.premium.list.priority') }}</li>
          <li>{{ __('modals.premium.list.support') }}</li>
        </ul>
        <div class="alert alert-info small mb-0">
          {{ __('modals.premium.alert') }}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button"
                class="btn btn-primary"
                data-bs-target="#{{ $subscribeModalId ?? 'subscribeModal' }}"
                data-bs-toggle="modal"
                data-bs-dismiss="modal">
          {{ __('modals.premium.btn.subscribe_now') }}
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('modals.premium.btn.later') }}</button>
      </div>
    </div>
  </div>
</div>
