{{-- resources/views/theme/conversations/modals/exchange.blade.php --}}
@php
  $modalId = $modalId ?? 'exchangeModal';
  $formId  = $formId  ?? 'exchangeForm';
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="{{ $formId }}" class="modal-content" action="{{ route('conversations.exchanges.store', $conversation) }}" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('modals.exchange.title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">{{ __('modals.exchange.choose_my_skill') }}</label>
          <select name="sender_skill_id" class="form-select" required>
            <option value="" disabled selected>— {{ __('common.choose') }} —</option>
            @foreach(($mySkills ?? []) as $mySkill)
              <option value="{{ $mySkill->id }}">{{ $mySkill->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('modals.exchange.choose_their_skill') }}</label>
          <select name="receiver_skill_id" class="form-select" required>
            <option value="" disabled selected>— {{ __('common.choose') }} —</option>
            @foreach(($theirSkills ?? []) as $theirSkill)
              <option value="{{ $theirSkill->id }}">{{ $theirSkill->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('modals.exchange.message_for_receiver') }}</label>
          <textarea name="message_for_receiver" class="form-control" rows="3" placeholder="{{ __('modals.exchange.message_placeholder') }}"></textarea>
        </div>
        <div class="alert alert-info small">
          {!! __('modals.exchange.info_html') !!}
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit" id="exchangeSendBtn">{{ __('modals.exchange.submit') }}</button>
      </div>
    </form>
  </div>
</div>
