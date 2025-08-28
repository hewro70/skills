@extends('theme.master')

@section('content')
    <div class="container mt-4" style="min-height: 70vh;">
        <h4 class="mb-4 text-center">{{ __('invitations.title_received') }}</h4>

        @forelse($invitations as $invitation)
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div>
                        <strong>{{ $invitation->sourceUser->fullName() }}</strong>
                        <p class="mb-1 text-muted small">
                            {{ __('invitations.sent_at', [
                                'date' => $invitation->date_time->translatedFormat('Y-m-d H:i')
                            ]) }}
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        @if ($invitation->reply)
                            <span class="badge bg-secondary">
                                {{ __('invitations.replied', ['reply' => $invitation->reply]) }}
                            </span>
                        @else
                            <div class="d-inline">
                                <button type="button"
                                        class="btn btn-success btn-sm me-1 reply-btn"
                                        data-url="{{ route('invitations.reply', $invitation) }}"
                                        data-reply="قبول">
                                    {{ __('invitations.buttons.accept') }}
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-sm reply-btn"
                                        data-url="{{ route('invitations.reply', $invitation) }}"
                                        data-reply="رفض">
                                    {{ __('invitations.buttons.reject') }}
                                </button>
                            </div>
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
