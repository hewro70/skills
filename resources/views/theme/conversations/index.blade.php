@extends('theme.master')

@section('content')
    <div class="container-fluid conversations-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4 col-lg-3 sidebar">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">المحادثات</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($conversations as $conversation)
                                <a href="{{ route('conversations.show', $conversation) }}"
                                    class="list-group-item list-group-item-action {{ request()->is('conversations/' . $conversation->id) ? 'active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $otherUser = $conversation->users->where('id', '!=', auth()->id())->first();
                                        @endphp
                                        <img src="{{ $otherUser->image_url }}" class="rounded-circle me-3" width="40"
                                            height="40" alt="{{ $otherUser->fullName() }}">
                                        <div>
                                            <h6 class="mb-0">{{ $otherUser->fullName() }}</h6>
                                            <small class="text-muted">
                                                {{ $conversation->messages->first()->body ?? 'لا توجد رسائل' }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="list-group-item text-center py-4">
                                    <p>لا توجد محادثات</p>
                                    <a href="{{ route('conversations.create') }}" class="btn btn-primary">
                                        ابدأ محادثة جديدة
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8 col-lg-9 main-content">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                            <h4 class="mt-3">اختر محادثة لبدء الدردشة</h4>
                            <p class="text-muted">أو ابدأ محادثة جديدة مع أحد معارفك</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .conversations-container {
            height: calc(100vh - 150px);
        }

        .sidebar,
        .main-content {
            height: 100%;
        }

        .list-group-item {
            border-left: none;
            border-right: none;
        }
    </style>
@endpush
