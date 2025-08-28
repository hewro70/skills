{{-- resources/views/theme/conversations/modals/review.blade.php --}}
<div class="modal fade" id="{{ $modalId ?? 'reviewModal' }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="{{ $formId ?? 'reviewForm' }}" class="modal-content"
          action="{{ route('conversations.reviews.store', $conversation) }}"
          method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('modals.review.title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">{{ __('modals.review.rating') }}</label>
          <select name="ratings" class="form-select" required>
            <option value="" disabled selected>— {{ __('common.choose') }} —</option>
            <option value="5">{{ __('modals.review.rating.5') }}</option>
            <option value="4">{{ __('modals.review.rating.4') }}</option>
            <option value="3">{{ __('modals.review.rating.3') }}</option>
            <option value="2">{{ __('modals.review.rating.2') }}</option>
            <option value="1">{{ __('modals.review.rating.1') }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('modals.review.comment') }}</label>
          <textarea name="comment" class="form-control" rows="3" placeholder="{{ __('modals.review.comment_placeholder') }}"></textarea>
        </div>
        <div class="alert alert-info small">
          {{ __('modals.review.hint') }}
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit" id="reviewSendBtn">{{ __('modals.review.submit') }}</button>
      </div>
    </form>
  </div>
</div>
