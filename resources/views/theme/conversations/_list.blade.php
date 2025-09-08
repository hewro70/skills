{{-- resources/views/theme/conversations/_list.blade.php --}}

@php
  $invCount = ($inviteCandidates ?? collect())->count();
@endphp

@push('styles')
<style>
  /* ===== شكل عام أجمل ===== */
  .conv-card { border: none; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,.06); overflow: hidden; }
  .conv-card .card-header { border-bottom: 0; }
  .invite-toggle-btn { border-radius: .9rem; box-shadow: 0 6px 18px rgba(25,135,84,.15); }
  .invite-box .list-group-item { border: 0; border-radius: .75rem; box-shadow: 0 8px 20px rgba(0,0,0,.05); }
  .invite-box .list-group-item + .list-group-item { margin-top: .75rem; }

  .avatar-50 { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
  .msg-preview { max-width: 540px; color: #6b7280; }
  .conv-item { border: 0; border-radius: .75rem; padding: .9rem 1rem; transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease; }
  .conv-item:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(0,0,0,.06); background: #f9fafb; }

  .chip { display: inline-block; padding: .25rem .6rem; border-radius: 999px; font-size: .75rem; background: #eef2ff; color: #3730a3; font-weight: 600; }
  .chip-muted { background: #f3f4f6; color: #6b7280; }

  /* نقطة “غير مُرَدّ عليها” */
  .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
  .dot-unreplied { background: #ef4444; box-shadow: 0 0 0 3px #fee2e2 inset; }

  /* زر الإرسال */
  .send-message-btn { border-radius: .7rem; }
</style>
@endpush

{{-- ===== زر يفتح قائمة المرشّحين (يظهر فقط عند وجود بيانات) ===== --}}
@if($invCount > 0)
  <button class="btn btn-success w-100 mb-3 d-flex align-items-center justify-content-between invite-toggle-btn"
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
    <div class="card invite-box mb-4 conv-card">
      <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
        <span>{{ __('conversations.invite.header') }}</span>
        <span class="chip chip-muted">{{ $invCount }} {{ __('common.count') }}</span>
      </div>
      <div class="card-body">
        <div class="list-group">

          @foreach($inviteCandidates as $item)
            @php $user = $item->otherUser; @endphp
            @continue(!$user)

            <form action="{{ route('conversations.store') }}" method="POST"
                  class="conversation-form" data-user-id="{{ $user->id }}">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">

              <div class="list-group-item d-flex align-items-start">
                <img
                  src="{{ $user->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? ($user->full_name ?? 'User')) }}"
                  class="avatar-50 me-3"
                  alt="{{ $user->fullName() ?? $user->name ?? __('common.user') }}">

                <div class="flex-grow-1">
                  <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-1">
                      {{ $user->fullName()
                          ?? $user->full_name
                          ?? trim(($user->first_name ?? '').' '.($user->last_name ?? ''))
                          ?? $user->name
                          ?? $user->email }}
                    </h6>
                    <small class="text-muted">{{ optional($item->ts)->diffForHumans() }}</small>
                  </div>

                  <textarea name="message" class="form-control mt-2" rows="2"
                            placeholder="{{ __('conversations.invite.message_placeholder') }}" required></textarea>

                  <div class="d-flex align-items-center gap-2 mt-2">
                    <button type="submit"
                            class="btn btn-success btn-sm send-message-btn"
                            data-sending-text="{{ __('conversations.actions.sending') }}"
                            data-send-text="{{ __('conversations.actions.send') }}">
                      <span class="btn-text">{{ __('conversations.actions.send') }}</span>
                      <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                    <span class="chip chip-muted"><i class="bi bi-shield-check me-1"></i>{{ __('conversations.invite.safe_start') }}</span>
                  </div>

                  <div class="error-message text-danger small mt-2 d-none"></div>
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
<div class="card conv-card">
  <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
    <span>{{ __('conversations.list.header') }}</span>
    {{-- تلميحة بسيطة --}}
    <span class="chip chip-muted"><i class="bi bi-chat-dots me-1"></i>{{ __('conversations.list.tip') }}</span>
  </div>

  <div class="card-body">
    <div class="list-group">
      @forelse($conversations as $conversation)
        @php
          $me    = auth()->id();
          $other = $conversation->users->firstWhere('id', '!=', $me);
          $last  = $conversation->relationLoaded('lastMessage')
                    ? $conversation->lastMessage
                    : ($conversation->messages[0] ?? null);

          $unreplied = $last && (int)$last->user_id !== (int)$me; // آخر رسالة من الطرف الآخر
        @endphp

        <a href="{{ route('conversations.show', $conversation) }}"
           class="list-group-item list-group-item-action d-flex align-items-center conv-item">
          <img
            src="{{ $other?->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name ?? ($other->full_name ?? 'User')) }}"
            class="avatar-50 me-3"
            alt="{{ $other?->fullName() ?? $other?->name ?? __('common.user') }}">

          <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between">
              <h6 class="mb-1 d-flex align-items-center gap-2">
                {{ $other?->fullName()
                    ?? $other?->full_name
                    ?? trim(($other->first_name ?? '').' '.($other->last_name ?? ''))
                    ?? $other?->name
                    ?? $other?->email
                    ?? __('conversations.fallback.title') }}
                @if($unreplied)
                  <span class="dot dot-unreplied" title="{{ __('conversations.list.unreplied') }}"></span>
                @endif
              </h6>

              <small class="text-muted text-nowrap">
                {{ optional($conversation->last_message_at ?? $last?->created_at)->diffForHumans() }}
              </small>
            </div>

            <div class="msg-preview mt-1">
              <small>
                @if($last?->body)
                  {{ \Illuminate\Support\Str::limit($last->body, 80) }}
                @else
                  {{ __('conversations.list.no_messages_yet') }}
                @endif
              </small>
            </div>
          </div>
        </a>
      @empty
        <div class="text-muted px-2">{{ __('conversations.list.empty') }}</div>
      @endforelse
    </div>

    <div class="mt-3">
      {{ $conversations->appends(request()->except('page','_ajax'))->links() }}
    </div>
  </div>
</div>

@push('scripts')
<script>
  // فتح قائمة المرشّحين تلقائيًا لو وصلنا مع ?open=invites
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
        text.textContent = btn.dataset.sendText || '{{ __('conversations.actions.send') }}';
        btn.disabled = false;
      }
    });
  });
</script>
@endpush
