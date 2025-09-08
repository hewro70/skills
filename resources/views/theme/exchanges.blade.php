{{-- resources/views/theme/invitations.blade.php --}}
@extends('theme.master')

@section('content')
  <div class="container mt-4" style="min-height:70vh;">

    {{-- ===== Exchange Requests (incoming + outgoing) ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
      <h4 class="m-0">{{ __('exchanges.title') }}</h4>
      <form method="GET" class="d-flex gap-2">
        <label class="me-1 small text-muted align-self-center">{{ __('exchanges.filter.label') }}</label>
        <select name="status" class="form-select form-select-sm" style="max-width:220px;"
                onchange="this.form.submit()">
          @php $cur = $status ?? request('status'); @endphp
          <option value="" {{ empty($cur) ? 'selected' : '' }}>{{ __('exchanges.filter.all') }}</option>
          <option value="pending"   {{ $cur==='pending'   ? 'selected' : '' }}>{{ __('statuses.pending') }}</option>
          <option value="accepted"  {{ $cur==='accepted'  ? 'selected' : '' }}>{{ __('statuses.accepted') }}</option>
          <option value="rejected"  {{ $cur==='rejected'  ? 'selected' : '' }}>{{ __('statuses.rejected') }}</option>
          <option value="cancelled" {{ $cur==='cancelled' ? 'selected' : '' }}>{{ __('statuses.cancelled') }}</option>
        </select>
      </form>
    </div>

    @forelse($exchanges as $ex)
      @php
        $me = auth()->id();
        $incoming = ($ex->receiver_id === $me);
        $otherUser = $incoming ? $ex->sender : $ex->receiver;
      @endphp

      <div class="card mb-3 shadow-sm exchange-card" data-id="{{ $ex->id }}">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
          <div>
            <strong>{{ $otherUser?->fullName() ?? '—' }}</strong>
            <span class="badge bg-{{ $incoming ? 'info' : 'primary' }} ms-2 align-middle">
              {{ $incoming ? __('exchanges.badges.incoming') : __('exchanges.badges.outgoing') }}
            </span>
            <p class="mb-1 text-muted small">{{ optional($ex->created_at)->translatedFormat('Y-m-d H:i') }}</p>

            <div class="small">
              <span class="fw-semibold">
                {{ $incoming ? __('exchanges.fields.wants_to_learn_from_you') : __('exchanges.fields.wants_to_learn_from_them') }}
              </span>
              <span>{{ $ex->receiverSkill?->name ?? '—' }}</span>
            </div>
            <div class="small">
              <span class="fw-semibold">
                {{ $incoming ? __('exchanges.fields.will_teach_you') : __('exchanges.fields.will_teach_them') }}
              </span>
              <span>{{ $ex->senderSkill?->name ?? '—' }}</span>
            </div>

            @if($ex->message_for_receiver)
              <div class="small mt-2 text-muted">{{ __('exchanges.fields.message') }} {{ $ex->message_for_receiver }}</div>
            @endif

            @if($ex->conversation_id)
              <a class="btn btn-link p-0 mt-2"
                 href="{{ route('conversations.show', $ex->conversation_id) }}">
                {{ __('exchanges.actions.go_to_chat') }}
              </a>
            @endif
          </div>

          <div class="mt-2 mt-md-0 text-md-end">
            @if($ex->status === 'pending' && $incoming)
              <form class="d-inline ex-form"
                    method="POST"
                    action="{{ route('conversations.exchanges.accept', ['conversation'=>$ex->conversation_id,'exchange'=>$ex->id]) }}">
                @csrf
                <button class="btn btn-success btn-sm me-1">{{ __('exchanges.actions.accept_mutual') }}</button>
              </form>

              <form class="d-inline ex-form"
                    method="POST"
                    action="{{ route('conversations.exchanges.acceptTeachOnly', ['conversation'=>$ex->conversation_id,'exchange'=>$ex->id]) }}">
                @csrf
                <button class="btn btn-outline-success btn-sm me-1">{{ __('exchanges.actions.accept_teach_only') }}</button>
              </form>

              <form class="d-inline ex-form"
                    method="POST"
                    action="{{ route('conversations.exchanges.reject', ['conversation'=>$ex->conversation_id,'exchange'=>$ex->id]) }}">
                @csrf
                <button class="btn btn-danger btn-sm">{{ __('exchanges.actions.reject') }}</button>
              </form>

            @elseif($ex->status === 'pending' && !$incoming)
              <form class="d-inline ex-form"
                    method="POST"
                    action="{{ route('conversations.exchanges.cancel', ['conversation'=>$ex->conversation_id,'exchange'=>$ex->id]) }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm">{{ __('exchanges.actions.cancel') }}</button>
              </form>

            @else
              <span class="badge bg-secondary">
                {{ __('exchanges.filter.label') }}: {{ __('statuses.'.$ex->status) }}
              </span>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="alert alert-info text-center">{{ __('exchanges.empty') }}</div>
    @endforelse

    <div class="mt-3">
      {{ $exchanges->appends(request()->only('status'))->links() }}
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // localized strings for JS
  const tProcessed = @json(__('exchanges.processed'));
  const tError = @json(__('exchanges.error'));
  const tUnexpected = @json(__('exchanges.unexpected_error'));
  const tInvUpdatedPrefix = @json(__('invitations.updated_prefix'));

  // Reply to invitations via AJAX
  document.addEventListener('click', async function(e){
    const btn = e.target.closest('.reply-btn');
    if(!btn) return;

    try{
      const res = await fetch(btn.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reply: btn.dataset.reply }) // keep Arabic value if backend expects it
      });
      const data = await res.json();
      if(res.ok){
        const card = btn.closest('.invite-card');
        if(card) card.outerHTML = `<div class="alert alert-success mb-3">${tInvUpdatedPrefix}${btn.dataset.reply}</div>`;
      }else{
        alert(data.message || tError);
      }
    }catch(_){ alert(tUnexpected); }
  });

  // Exchange actions via AJAX
  document.addEventListener('submit', function(e){
    const form = e.target.closest('.ex-form');
    if(!form) return;
    e.preventDefault();

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(r => r.json().catch(()=>({})).then(data=>({ok:r.ok, data})))
    .then(({ok, data})=>{
      if(ok && (data.success === true || data.mode || data.message)){
        const card = form.closest('.exchange-card');
        if(card) card.outerHTML = `<div class="alert alert-success mb-3">${tProcessed}</div>`;
      }else{
        alert((data && (data.error || data.message)) || tError);
      }
    })
    .catch(()=> alert(tUnexpected));
  });
</script>
@endpush
