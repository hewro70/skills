{{-- resources/views/theme/conversations/show.blade.php --}}
@extends('theme.master')
@php
  $REVIEW_THRESHOLD = 12;
  $validMsgCount = ($messages ?? collect())->filter(function($m){
      return mb_strlen(trim($m->body ?? '')) >= 7;
  })->count();

  $reviewEligible = $validMsgCount >= $REVIEW_THRESHOLD;
  $remainingToReview = max(0, $REVIEW_THRESHOLD - $validMsgCount);
@endphp

@section('content')
  <div class="conversations-page d-flex flex-column min-vh-100">
    <div class="conversations-main flex-grow-1">
      <div class="container-fluid conversations-container py-3">
        <div class="row gx-4 h-100">

          {{-- ===== Sidebar ===== --}}
          <div class="col-md-4 col-lg-3 sidebar d-flex flex-column" style="min-height:0;">
            <div class="card shadow-sm flex-grow-1 d-flex flex-column" style="min-height:0;">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ __('conversations.sidebar.title') }}</h5>
              </div>
              <div class="card-body p-0 overflow-auto flex-grow-1" style="min-height:0;">
                <div class="list-group list-group-flush">
                  @foreach (auth()->user()->conversations as $conv)
                    @php $otherUserSidebar = $conv->users->where('id','!=',auth()->id())->first(); @endphp
                    <a href="{{ route('conversations.show', $conv) }}"
                       class="list-group-item list-group-item-action d-flex align-items-center {{ $conv->id == $conversation->id ? 'active bg-primary text-white' : '' }}">
                      <img src="{{ $otherUserSidebar->image_url ?? '' }}" class="rounded-circle me-3" width="45" height="45" alt="{{ $otherUserSidebar?->fullName() ?? __('common.user') }}">
                      <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $otherUserSidebar?->fullName() ?? __('common.user') }}</h6>
                        <small class="{{ $conv->id == $conversation->id ? 'text-white-50' : 'text-muted' }} text-truncate d-block" style="max-width:160px;">
                          {{ $conv->messages->first()?->body ?? __('conversations.messages.no_messages') }}
                        </small>
                      </div>
                    </a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          {{-- ===== Main Content ===== --}}
          <div class="col-md-8 col-lg-9 main-content d-flex flex-column" style="min-height:0;">
            <div class="card shadow-sm flex-grow-1 d-flex flex-column" style="min-height:0;">

      {{-- Header --}}
<div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div class="d-flex align-items-center flex-grow-1 min-w-0">
    <img src="{{ $otherUser->image_url }}" class="rounded-circle me-3" width="50" height="50" alt="{{ $otherUser->fullName() }}">
    <h5 class="mb-0 text-truncate">{{ $otherUser->fullName() }}</h5>
  </div>

  <div class="d-flex gap-2 header-actions">
    @php $lblReview = app()->getLocale() === 'ar' ? 'تقييم' : 'Review'; @endphp

    @if($reviewEligible)
      <button class="btn btn-success btn-sm" id="openReviewBtn" data-bs-toggle="modal" data-bs-target="#reviewModal" aria-label="{{ $lblReview }}">
        <i class="bi bi-hand-thumbs-up me-1"></i>
        <span>{{ $lblReview }}</span>
      </button>
    @else
      <button class="btn btn-outline-secondary btn-sm" id="openReviewBtn" disabled
              data-bs-toggle="tooltip"
              aria-label="{{ $lblReview }}"
              title="{{ trans_choice('conversations.review.remaining', $remainingToReview, ['n'=>$remainingToReview]) }}">
        <i class="bi bi-hand-thumbs-up me-1"></i>
        <span>{{ $lblReview }}</span>
      </button>
    @endif
  </div>
</div>



              {{-- Tabs --}}
              <ul class="nav nav-tabs px-3 pt-3" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="tab-messages" data-bs-toggle="tab" data-bs-target="#pane-messages" type="button" role="tab">
                    {{ __('conversations.tabs.messages') }}
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  {{--  <button class="nav-link" id="tab-exchanges" data-bs-toggle="tab" data-bs-target="#pane-exchanges" type="button" role="tab">
                    {{ __('conversations.tabs.exchanges') }}
                    @if(($exchanges ?? collect())->isNotEmpty())
                      <span class="badge bg-secondary align-middle">{{ $exchanges->count() }}</span>
                    @endif
                  </button>  --}}
                </li>
              </ul>

              {{-- Tabs Content --}}
              <div class="tab-content flex-grow-1 d-flex flex-column" style="min-height:0;">

                {{-- Messages --}}
                <div class="tab-pane fade show active flex-grow-1 d-flex flex-column" id="pane-messages" role="tabpanel" style="min-height:0;">
                  <div class="card-body messages-container flex-grow-1 overflow-y bg-white px-3 py-2" id="messages" style="max-height:70vh;">
                    <div id="messagesWrapper">
                      @foreach ($messages as $message)
                        <div class="message d-flex {{ $message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-2" data-id="{{ $message->id }}">
                          <div class="message-content p-3 rounded shadow {{ $message->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width:62%; word-wrap:break-word;">
                            <p class="mb-1">{{ $message->body }}</p>
                            <small class="{{ $message->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }} fst-italic" style="font-size:.75rem;">
                              {{ $message->created_at->diffForHumans() }}
                            </small>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="card-footer bg-light">
                    <form id="messageForm" action="{{ route('conversations.messages.store', $conversation) }}" method="POST" autocomplete="off">
                      @csrf
                      <div class="input-group">
                        <input type="text" name="body" id="messageInput" class="form-control" placeholder="{{ __('conversations.messages.input_placeholder') }}" required>
                        <button class="btn btn-primary" type="submit" id="sendBtn">{{ __('conversations.messages.send') }}</button>
                      </div>
                    </form>
                  </div>
                </div>

                {{-- Exchanges --}}
                <div class="tab-pane fade flex-grow-1 d-flex flex-column" id="pane-exchanges" role="tabpanel" style="min-height:0;">
                  <div class="card-body bg-white px-3 py-3 overflow-auto" style="min-height:0;">
                    @if(($exchanges ?? collect())->isNotEmpty())
                      @foreach($exchanges as $ex)
                        @php $iAmSender = $ex->sender_id===auth()->id(); $iAmReceiver = $ex->receiver_id===auth()->id(); @endphp
                        <div class="alert {{ $ex->status === 'pending' ? 'alert-warning' : ($ex->status === 'accepted' ? 'alert-success' : 'alert-secondary') }} d-flex align-items-center justify-content-between py-2">
                          <div>
                            <div class="fw-semibold mb-1">
                              {{ __('exchanges.card.title', [
                                  'id' => $ex->id,
                                  'senderSkill' => $ex->senderSkill?->name ?? 'N/A',
                                  'receiverSkill' => $ex->receiverSkill?->name ?? 'N/A'
                              ]) }}
                            </div>
                            <div class="small text-muted">
                              {{ __('exchanges.labels.sender') }}:
                              {{ $ex->sender?->fullName() ?? $ex->sender?->email }}
                              — {{ __('exchanges.labels.status') }}:
                              <span class="badge bg-{{ $ex->status === 'accepted' ? 'success' : ($ex->status === 'rejected' ? 'danger' : ($ex->status === 'pending' ? 'warning text-dark' : 'secondary')) }}">
                                {{ __("exchanges.status.$ex->status") }}
                              </span>
                              — {{ $ex->created_at->diffForHumans() }}
                            </div>
                            @if($ex->message_for_receiver)
                              <div class="small mt-1">{{ $ex->message_for_receiver }}</div>
                            @endif
                          </div>

                          <div class="ms-2 text-nowrap">
                            @if($ex->status === 'pending' && $iAmReceiver)
                              <button class="btn btn-success btn-sm ex-accept"
                                      data-url="{{ route('conversations.exchanges.accept', [$conversation,$ex]) }}">
                                {{ __('exchanges.buttons.accept') }}
                              </button>
                              <button class="btn btn-outline-primary btn-sm ex-accept"
                                      data-url="{{ route('conversations.exchanges.acceptTeachOnly', [$conversation,$ex]) }}">
                                {{ __('exchanges.buttons.accept_teach_only') }}
                              </button>
                              <button class="btn btn-outline-danger btn-sm ex-reject"
                                      data-url="{{ route('conversations.exchanges.reject', [$conversation,$ex]) }}">
                                {{ __('exchanges.buttons.reject') }}
                              </button>
                            @elseif($ex->status === 'pending' && $iAmSender)
                              <button class="btn btn-outline-secondary btn-sm ex-cancel"
                                      data-url="{{ route('conversations.exchanges.cancel', [$conversation,$ex]) }}">
                                {{ __('exchanges.buttons.cancel') }}
                              </button>
                            @elseif($ex->status === 'accepted')
                              <button class="btn btn-outline-success btn-sm"
                                      data-bs-toggle="modal"
                                      data-bs-target="#reviewModal">
                                {{ __('exchanges.buttons.review_other') }}
                              </button>
                            @endif
                          </div>
                        </div>
                      @endforeach
                    @else
                      <div class="alert alert-info">{{ __('conversations.exchanges.none') }}</div>
                    @endif
                  </div>
                  <div class="card-footer bg-light d-flex justify-content-end">
                    {{--  <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exchangeModal">
                      <i class="bi bi-arrow-left-right me-1"></i> {{ __('conversations.header.exchange_request_button') }}
                    </button>  --}}
                  </div>
                </div>

              </div> {{-- /tab-content --}}
            </div>   {{-- /card --}}
          </div>     {{-- /main-content --}}

        </div>
      </div>
    </div>

    {{-- ===== Modals (included) ===== --}}
    @include('theme.conversations.modals.review', [
      'modalId' => 'reviewModal',
      'formId'  => 'reviewForm',
      'conversation' => $conversation,
    ])

    @include('theme.conversations.modals.premium', [
      'modalId' => 'premiumModal',
      'subscribeModalId' => 'subscribeModal',
    ])

    @include('theme.conversations.modals.subscribe', [
      'modalId' => 'subscribeModal',
      'formId'  => 'subscribeForm',
      // إعدادات افتراضية قابلة للتغيير
      'config'  => [
        'price'        => '10 USD',
        'wallet_email' => 'wallet@example.com',
        'wallet_name'  => 'Premium Wallet',
        'paypal'       => 'payments@yourapp.com',
      ],
      'prefill' => [
        'email'        => auth()->user()->email ?? '',
        'account_name' => auth()->user()->name ?? 'your_account',
      ],
    ])

    {{--  @include('theme.conversations.modals.exchange', [
      'modalId' => 'exchangeModal',
      'formId'  => 'exchangeForm',
      'conversation' => $conversation,
      'mySkills' => auth()->user()->skills,
      'theirSkills' => $otherUser->skills,
    ])  --}}
  </div> {{-- /conversations-page --}}
@endsection

@push('styles')
<style>
/* (نفس الستايل الذي أرسلته) */
.conversations-page,
.conversations-main,
.conversations-container,
.sidebar,
.main-content,
.main-content .card,
.tab-content,
.tab-pane { min-height: 0 !important; }
.main-content .card { display: flex; flex-direction: column; }
.tab-content { display: flex; flex: 1 1 auto; }
.tab-pane { display: flex !important; flex-direction: column; flex: 1 1 auto; }
#premiumModal .modal-header { border-bottom: 0; }
#premiumModal .modal-footer { border-top: 0; }
#messages { flex: 1 1 auto; overflow: auto; }
#messagesWrapper { display:flex; flex-direction:column; gap:.4rem; padding-bottom:.25rem; }
.message { width: 100%; }
.message .message-content{ max-width:64%; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
@media (max-width: 992px){ .message .message-content{ max-width:80%; } }
#pane-exchanges .card-body{ flex: 1 1 auto; overflow: auto; padding-top: .6rem; display: flex; flex-direction: column; justify-content: flex-start; }
#pane-exchanges .card-body > .alert { margin-bottom: .6rem; }
#pane-exchanges .card-body > .alert:first-child { margin-top: .2rem; }
.nav-tabs{ border-bottom:1px solid #e9ecef; }
.nav-tabs .nav-link{ border:0; border-bottom:2px solid transparent; color:#6b7280; }
.nav-tabs .nav-link.active{ color:#0d6efd; border-color:#0d6efd; font-weight:600; }
.tab-pane:not(.active){ display:none !important; }
.sidebar .card-body{ max-height: calc(100vh - 190px); overflow: auto; }
.card-header .rounded-circle{ object-fit: cover; }
#pane-messages .card-footer,
#pane-exchanges .card-footer{ padding:.5rem .75rem; border-top:1px solid #eef1f4; }
</style>
@endpush
@push('styles')
<style>
/* --- تحسينات استجابة زر Review على الموبايل --- */
.card-header .header-actions{ flex-wrap:wrap; }
@media (max-width: 576px){
  .card-header{ row-gap:.5rem; }
  .card-header .header-actions{ width:100%; }
  .card-header .header-actions .btn{
    flex:1 1 48%;
    white-space:nowrap;
  }
  /* اسم المستخدم ما يلف ويقص بطريقة أنظف */
  .card-header h5.text-truncate{ max-width: 65vw; }
}
</style>
@endpush


@push('scripts')
<script>
/* === i18n bridge for JS === */
(function () {
  window.T = {
    common: {
      choose: @json(__('common.choose')),
      close:  @json(__('common.close')),
      cancel: @json(__('common.cancel'))
    },
    conversations: {
      messages: {
        send:           @json(__('conversations.messages.send')),
        now:            @json(__('conversations.messages.now')),
        loading_older:  @json(__('conversations.messages.loading_older')),
        no_messages:    @json(__('conversations.messages.no_messages')),
        input_placeholder: @json(__('conversations.messages.input_placeholder'))
      },
      errors: {
        send_failed:    @json(__('errors.send_failed')),
        load_failed:    @json(__('errors.load_failed')),
        operation_failed:@json(__('errors.operation_failed'))
      }
    },
    actions: {
      sending: @json(__('actions.sending'))
    },
    swal: {
      error:   @json(__('swal.default.error')),
      ok:      @json(__('swal.default.ok')),
      success: @json(__('swal.default.success'))
    },
    toasts: {
      request_received:   @json(__('toasts.success.request_received')),
      premium_activation: @json(__('toasts.success.premium_activation'))
    },
    premium: {
      upsell_trigger_text_1: @json(__('premium.limit_trigger_1')),
      upsell_trigger_2: @json(__('premium.limit_trigger_2'))
    }
  };
})();
</script>

<script>
/* === Helpers === */
function showPremiumAfterClosingExchange() {
  const exEl = document.getElementById('exchangeModal');
  const prEl = document.getElementById('premiumModal');
  if (!exEl || !prEl || !window.bootstrap) return;

  const exInst = bootstrap.Modal.getInstance(exEl) || new bootstrap.Modal(exEl);
  const prInst = bootstrap.Modal.getInstance(prEl) || new bootstrap.Modal(prEl);

  const handler = () => { exEl.removeEventListener('hidden.bs.modal', handler); prInst.show(); };
  exEl.addEventListener('hidden.bs.modal', handler);
  exInst.hide();
}

function swalError(msg){ Swal.fire({icon:'error', title:T.swal.error, text: msg || T.conversations.errors.operation_failed, confirmButtonText:T.swal.ok}); }
function swalSuccess(title, text){ Swal.fire({icon:'success', title: title || T.swal.success, text: text || '', timer:1800, showConfirmButton:false}); }
</script>

<script>
/* === Chat core (send, load older, Echo) === */
$(function () {
  const $messages   = $('#messages');
  const $wrapper    = $('#messagesWrapper');
  const $form       = $('#messageForm');
  const $input      = $('#messageInput');
  const $send       = $('#sendBtn');

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept':'application/json' }
  });

  setTimeout(()=> $messages.scrollTop($messages[0].scrollHeight), 300);

  $form.on('submit', function(e){
    e.preventDefault();
    const body = ($input.val()||'').trim();
    if(!body) return;

    $send.prop('disabled', true);
    const old = $send.html();
    $send.html('<span class="spinner-border spinner-border-sm"></span> ' + T.actions.sending);

    $.post({ url: $form.attr('action'), data: { body }, dataType: 'json' })
     .done(res=>{
       if(res?.success){
         $wrapper.append(createMessageElement(res.message, true));
         $messages.scrollTop($messages[0].scrollHeight);
         $input.val('');
         $(`a[href*="/conversations/${res.message.conversation_id}"] small`).text(res.message.body);
       } else {
         swalError(T.conversations.errors.send_failed);
       }
     })
     .fail(()=> swalError(T.conversations.errors.send_failed))
     .always(()=> { $send.prop('disabled', false).html(old); });
  });

  // Load older
  $messages.on('scroll', function(){
    if ($messages.scrollTop() < 100 && !$messages.data('loading') && $messages.data('hasmore') !== false) {
      loadOlder();
    }
  });

  function loadOlder(){
    $messages.data('loading', true);
    const firstId = $('.message').first().data('id');
    if(!firstId){ $messages.data('loading', false); return; }
    const loading = $('<div class="text-center py-2"></div>').text(T.conversations.messages.loading_older);
    $wrapper.prepend(loading);

    $.get({
      url: '{{ route('conversations.show', $conversation) }}',
      data: { before: firstId, _ajax: true },
      headers: { 'X-Requested-With':'XMLHttpRequest' }
    })
    .done(res=>{
      loading.remove();
      if(res?.html){
        const prevScroll = $messages.scrollTop(), prevH = $messages[0].scrollHeight;
        $wrapper.prepend(res.html);
        const newH = $messages[0].scrollHeight;
        $messages.scrollTop(prevScroll + (newH - prevH));
        if(res.hasMore === false) $messages.data('hasmore', false);
      }
    })
    .fail(()=> swalError(T.conversations.errors.load_failed))
    .always(()=> $messages.data('loading', false));
  }

  // build bubble
  window.createMessageElement = function(message, isOwn){
    const msgClass = isOwn ? 'justify-content-end' : 'justify-content-start';
    const bgClass  = isOwn ? 'bg-primary text-white' : 'bg-light text-dark';
    const timeCls  = isOwn ? 'text-white-50' : 'text-muted';
    return `
      <div class="message d-flex ${msgClass} mb-2" data-id="${message.id}">
        <div class="message-content p-3 rounded shadow ${bgClass}" style="max-width:62%; word-wrap:break-word;">
          <p class="mb-1">${$('<div>').text(message.body).html()}</p>
          <small class="${timeCls} fst-italic" style="font-size:.75rem;">${T.conversations.messages.now}</small>
        </div>
      </div>`;
  };

  // Exchange action buttons (accept/reject/cancel)
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('.ex-accept, .ex-reject, .ex-cancel'); if(!btn) return;
    const url = btn.dataset.url; const old = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>…';
    try{
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept':'application/json' }
      });
      let data = {}; try { data = await res.json(); } catch {}
      if(!res.ok || !data.success){ throw data?.error || data?.message || T.conversations.errors.operation_failed; }
      swalSuccess();
      setTimeout(()=> location.reload(), 700);
    }catch(err){
      swalError(typeof err==='string' ? err : (err?.message || T.conversations.errors.operation_failed));
    }finally{
      btn.disabled = false; btn.innerHTML = old;
    }
  });

  // Echo (اختياري)
  (function initEcho(){
    if (!window.Echo) return;
    window.Echo.private(`conversation.{{ $conversation->id }}`)
      .listen('.message.sent', (data) => {
        if (String(data.message.user_id) === String({{ auth()->id() }})) return;
        const html = window.createMessageElement(data.message, false);
        $wrapper.append(html);
        const nearBottom = $messages.scrollTop() + $messages.innerHeight() >= $messages[0].scrollHeight - 50;
        if (nearBottom) $messages.scrollTop($messages[0].scrollHeight);
        $(`a[href*="/conversations/${data.message.conversation_id}"] small`).text(data.message.body);
      })
      .error((e)=> console.error('Echo error:', e));
  })();
});
</script>
@endpush

{{-- سكربتات المودالات القابلة لإعادة الاستخدام --}}
@include('theme.conversations.modals._review_scripts',   ['modalId'=>'reviewModal','formId'=>'reviewForm'])
@include('theme.conversations.modals._subscribe_scripts',['modalId'=>'subscribeModal','formId'=>'subscribeForm','action'=> route('premium.requests.store') ])
{{--  @include('theme.conversations.modals._exchange_scripts', ['modalId'=>'exchangeModal','formId'=>'exchangeForm','tabExchangesBtn'=>'tab-exchanges'])  --}}