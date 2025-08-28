{{-- resources/views/theme/conversations/_list.blade.php --}}

@php
  $invCount = ($inviteCandidates ?? collect())->count();
@endphp

{{-- ===== زر يفتح قائمة المرشّحين (يظهر فقط عند وجود بيانات) ===== --}}
@if($invCount > 0)
  <button class="btn btn-success w-100 mb-3 d-flex align-items-center justify-content-between"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#inviteCollapse"
          aria-expanded="false"
          aria-controls="inviteCollapse">
    <span class="d-flex align-items-center">
      <i class="bi bi-person-plus me-2"></i>
      {{ __('conversations.invite.toggle') }}
    </span>
    <span class="badge bg-light text-success">{{ $invCount }}</span>
  </button>

  <div class="collapse" id="inviteCollapse">
    <div class="card mb-4">
      <div class="card-header bg-success text-white">{{ __('conversations.invite.header') }}</div>
      <div class="card-body">
        <div class="list-group">

          @foreach($inviteCandidates as $item)
            @php $user = $item->otherUser; @endphp
            @continue(!$user)

            <form action="{{ route('conversations.store') }}" method="POST"
                  class="conversation-form mb-3" data-user-id="{{ $user->id }}">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">

              <div class="list-group-item d-flex align-items-start">
                <img
                  src="{{ $user->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? ($user->full_name ?? 'User')) }}"
                  class="rounded-circle me-3" width="50" height="50"
                  alt="{{ $user->fullName() ?? $user->name ?? __('common.user') }}">

                <div class="flex-grow-1">
                  <h6 class="mb-1">
                    {{ $user->fullName()
                        ?? $user->full_name
                        ?? trim(($user->first_name ?? '').' '.($user->last_name ?? ''))
                        ?? $user->name
                        ?? $user->email }}
                  </h6>

                  <small class="text-muted">{{ optional($item->ts)->diffForHumans() }}</small>

                  <textarea name="message" class="form-control mt-2" rows="2"
                            placeholder="{{ __('conversations.invite.message_placeholder') }}" required></textarea>

                  <button type="submit"
                          class="btn btn-success btn-sm mt-2 send-message-btn"
                          data-sending-text="{{ __('conversations.actions.sending') }}"
                          data-send-text="{{ __('conversations.actions.send') }}">
                    <span class="btn-text">{{ __('conversations.actions.send') }}</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                  </button>
                  <div class="error-message text-danger small mt-1 d-none"></div>
                </div>
              </div>
            </form>
          @endforeach

        </div>
      </div>
    </div>
  </div>
@endif

{{-- ===== محادثاتك ===== --}}
<div class="card">
  <div class="card-header bg-primary text-white">{{ __('conversations.list.header') }}</div>
  <div class="card-body">
    <div class="list-group">
      @forelse($conversations as $conversation)
        @php
          $me    = auth()->id();
          $other = $conversation->users->firstWhere('id', '!=', $me);
          $last  = $conversation->relationLoaded('lastMessage')
                    ? $conversation->lastMessage
                    : ($conversation->messages[0] ?? null);
        @endphp

        <a href="{{ route('conversations.show', $conversation) }}"
           class="list-group-item list-group-item-action d-flex align-items-start">

          <img
            src="{{ $other?->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name ?? ($other->full_name ?? 'User')) }}"
            class="rounded-circle me-3" width="50" height="50"
            alt="{{ $other?->fullName() ?? $other?->name ?? __('common.user') }}">

          <div class="flex-grow-1">
            <h6 class="mb-1">
              {{ $other?->fullName()
                  ?? $other?->full_name
                  ?? trim(($other->first_name ?? '').' '.($other->last_name ?? ''))
                  ?? $other?->name
                  ?? $other?->email
                  ?? __('conversations.fallback.title') }}
            </h6>

            <small class="text-muted">
              {{ $last?->body ? \Illuminate\Support\Str::limit($last->body, 60) : __('conversations.list.no_messages_yet') }}
            </small>
          </div>

          <div class="text-nowrap ms-2 small text-muted">
            {{ optional($conversation->last_message_at ?? $last?->created_at)->diffForHumans() }}
          </div>
        </a>
      @empty
        <div class="text-muted">{{ __('conversations.list.empty') }}</div>
      @endforelse
    </div>

    <div class="mt-3">
      {{ $conversations->appends(request()->except('page','_ajax'))->links() }}
    </div>
  </div>
</div>

@push('scripts')
<script>
  // فتح القائمة تلقائيًا لو وصلنا مع ?open=invites
  (function () {
    const params = new URLSearchParams(window.location.search);
    if (params.get('open') === 'invites') {
      const collapseEl = document.getElementById('inviteCollapse');
      if (collapseEl) {
        const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });
        bsCollapse.show();
      }
    }
  })();

  // AJAX لإرسال أول رسالة (يحترم i18n من data-attrs)
  document.querySelectorAll('.conversation-form').forEach(function (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();

      const btn  = form.querySelector('.send-message-btn');
      const text = btn.querySelector('.btn-text');
      const spin = btn.querySelector('.spinner-border');
      const err  = form.querySelector('.error-message');

      err.classList.add('d-none');
      spin.classList.remove('d-none');
      text.textContent = btn.dataset.sendingText || '...';
      btn.disabled = true;

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
          },
          body: new FormData(form)
        });

        const data = await res.json();

        if (!res.ok || !data.success) {
          throw new Error(data.error || @json(__('conversations.errors.send_failed')));
        }

        if (data.conversation_id) {
          window.location.href = `/conversations/${data.conversation_id}`;
        } else {
          window.location.reload();
        }

      } catch (e2) {
        err.textContent = e2.message || @json(__('conversations.errors.unexpected'));
        err.classList.remove('d-none');
      } finally {
        spin.classList.add('d-none');
        text.textContent = btn.dataset.sendText || '';
        btn.disabled = false;
      }
    });
  });
</script>
@endpush
