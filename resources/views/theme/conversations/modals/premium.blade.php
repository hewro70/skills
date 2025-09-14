{{-- resources/views/theme/conversations/modals/premium.blade.php --}}
<div class="modal fade mt-5" id="{{ $modalId ?? 'premiumModal' }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-warning-subtle">
        <h5 class="modal-title">
          <i class="bi bi-star-fill text-warning me-2"></i> {{ __('plans.choose') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>

      <div class="modal-body">
        <div class="row g-4">

          {{-- Free Plan --}}
          <div class="col-md-6">
            <div class="plan-card h-100 border rounded-4 p-4 text-center">
              <div class="mb-2"><i class="bi bi-star fs-2 text-secondary"></i></div>
              <h4 class="fw-bold">{{ __('plans.free') }}</h4>
              <p class="text-muted small">{{ __('plans.free_desc') }}</p>
              <h5 class="fw-bold mb-3">{{ __('plans.free_price') }}</h5>
              <ul class="list-unstyled text-start small mb-4">
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.free_invites') }}</li>
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.no_message') }}</li>
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.ads_visible') }}</li>
              </ul>
              <button class="btn btn-outline-secondary w-100" disabled>{{ __('plans.current') }}</button>
            </div>
          </div>

          {{-- Premium Plan --}}
          <div class="col-md-6">
            <div class="plan-card h-100 border rounded-4 p-4 text-center highlight">
              <div class="mb-2"><i class="bi bi-crown fs-2 text-warning"></i></div>
              <h4 class="fw-bold">{{ __('plans.premium') }}</h4>
              <p class="text-muted small">{{ __('plans.premium_desc') }}</p>
              <h5 class="fw-bold mb-3">{{ __('plans.premium_price') }}</h5>
              <ul class="list-unstyled text-start small mb-4">
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.unlimited_invites') }}</li>
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.personal_message') }}</li>
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.full_filters') }}</li>
                <li><i class="bi bi-check2 text-success me-1"></i> {{ __('plans.no_ads') }}</li>
              </ul>
              <button type="button"
                      class="btn btn-primary w-100"
                      data-bs-target="#{{ $subscribeModalId ?? 'subscribeModal' }}"
                      data-bs-toggle="modal"
                      data-bs-dismiss="modal">
                {{ __('plans.upgrade') }}
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


@push('styles')
<style>
  .plan-card{ background:#fff; transition:all .2s ease; }
  .plan-card.highlight{ border:2px solid #6c63ff; box-shadow:0 0 0 .2rem rgba(108,99,255,.1); }
  .plan-card:hover{ transform:translateY(-3px); }
</style>
@endpush
