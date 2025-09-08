{{-- resources/views/theme/invitations.blade.php --}}
@extends('theme.master')

@push('styles')
<style>
  .invite-card{ border:1px solid #e9ecef; border-radius:14px; }
  .invite-avatar{ width:44px; height:44px; border-radius:50%; object-fit:cover; }
</style>
@endpush

@section('content')
  <div class="container mt-4" style="min-height:70vh;">
    <h4 class="mb-4 text-center">{{ __('invitations.title_received') }}</h4>

    @forelse($invitations as $invitation)
      @php
        $sender      = $invitation->sourceUser;
        $senderName  = $sender?->fullName() ?? __('common.user');
        $avatar      = $sender?->image_url ?? asset('img/avatar-placeholder.png');

        // تاريخ العرض حسب لوكال التطبيق
        $dt   = $invitation->date_time ?? $invitation->created_at;
        $date = $dt ? $dt->locale(app()->getLocale())->isoFormat('YYYY-MM-DD HH:mm') : '';

        // نص الرسالة: إن وُجدت رسالة المرسل (بريميوم) نعرضها، غير هيك نظهر رسالة النظام
        $text = filled($invitation->message)
                  ? $invitation->message
                  : __('invitations.free.system_notice', ['name' => $senderName]);

        // نحاول نجيب conversation id لو الدعوة مقبولة، عشان نعرض زر "فتح المحادثة"
        $conversationId = null;
        if ($invitation->reply === 'قبول') {
          $meId    = auth()->id();
          $otherId = $invitation->source_user_id;
          $conv = \App\Models\Conversation::whereHas('users', fn($q)=>$q->where('users.id', $meId))
                  ->whereHas('users', fn($q)=>$q->where('users.id', $otherId))
                  ->first();
          $conversationId = $conv?->id;
        }
      @endphp

      <div class="card mb-3 shadow-sm invite-card" data-id="{{ $invitation->id }}">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <img class="invite-avatar" src="{{ $avatar }}" alt="{{ $senderName }}">
              <div>
                <h6 class="mb-0 fw-bold">{{ $senderName }}</h6>
                <small class="text-muted">{{ __('invitations.sent_at', ['date' => $date]) }}</small>
              </div>
            </div>

            <div class="ms-2">
              @if ($invitation->reply)
                <span class="badge bg-secondary">{{ __('invitations.replied', ['reply' => $invitation->reply]) }}</span>
              @else
                <button type="button"
                        class="btn btn-success btn-sm reply-btn me-1"
                        data-url="{{ route('invitations.reply', $invitation) }}"
                        data-reply="قبول">
                  {{ __('invitations.buttons.accept') }}
                </button>
                <button type="button"
                        class="btn btn-outline-danger btn-sm reply-btn"
                        data-url="{{ route('invitations.reply', $invitation) }}"
                        data-reply="رفض">
                  {{ __('invitations.buttons.reject') }}
                </button>
              @endif
            </div>
          </div>

          {{-- نص الدعوة --}}
          <div class="mt-3">
            <div class="border rounded p-2 bg-light">{!! nl2br(e($text)) !!}</div>

            {{-- زر فتح الشات إذا الدعوة مقبولة وعندنا محادثة --}}
            @if($conversationId)
              <a href="{{ route('conversations.show', $conversationId) }}"
                 class="btn btn-primary btn-sm mt-2">
                {{ __('conversations.fallback.title') }}
              </a>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="alert alert-info text-center">{{ __('invitations.empty') }}</div>
    @endforelse

    <div class="mt-3">
      {{ $invitations->links() }}
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // نصوص محلية
  const tError       = @json(__('exchanges.error'));
  const tUnexpected  = @json(__('exchanges.unexpected_error'));
  const tUpdatedPref = @json(__('invitations.updated_prefix'));

  // قالب رابط المحادثة (نبدّل __ID__ بالـconversation_id)
  const chatUrlTemplate = @json(route('conversations.show', ['conversation' => '__ID__']));

  // الرد على الدعوات عبر AJAX
  document.addEventListener('click', async function(e){
    const btn = e.target.closest('.reply-btn');
    if(!btn) return;

    btn.disabled = true;
    const oldHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';

    try{
      const res = await fetch(btn.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reply: btn.dataset.reply })
      });

      const data = await res.json();

      if(res.ok){
        // لو رجع conversation_id بعد القبول، نفتح الشات مباشرة
        if (data.conversation_id) {
          const url = chatUrlTemplate.replace('__ID__', String(data.conversation_id));
          window.location.href = url;
          return;
        }

        // غير ذلك: استبدل الكارت بتنبيه نجاح بسيط
        const card = btn.closest('.invite-card');
        if(card){
          card.outerHTML = `<div class="alert alert-success mb-3">${tUpdatedPref}${btn.dataset.reply}</div>`;
        }
      } else {
        alert(data.message || tError);
        btn.disabled = false; btn.innerHTML = oldHtml;
      }
    }catch(_){
      alert(tUnexpected);
      btn.disabled = false; btn.innerHTML = oldHtml;
    }
  });
</script>
@endpush
