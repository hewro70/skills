@extends('theme.master')

@section('content')
    <div class="container-fluid conversations-container py-3">
        <div class="row gx-4 h-100">
            <!-- Sidebar -->
            <div class="col-md-4 col-lg-3 sidebar">
                <div class="card shadow-sm h-100 d-flex flex-column">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">المحادثات</h5>
                    </div>
                    <div class="card-body p-0 overflow-auto flex-grow-1">
                        <div class="list-group list-group-flush">
                            @foreach (auth()->user()->conversations as $conv)
                                @php
                                    $otherUserSidebar = $conv->users->where('id', '!=', auth()->id())->first();
                                @endphp
                                <a href="{{ route('conversations.show', $conv) }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center
                                    {{ $conv->id == $conversation->id ? 'active bg-primary text-white' : '' }}">
                                    <img src="{{ $otherUserSidebar->image_url }}" class="rounded-circle me-3" width="45"
                                        height="45" alt="{{ $otherUserSidebar->fullName() }}">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $otherUserSidebar->fullName() }}</h6>
                                        <small
                                            class="{{ $conv->id == $conversation->id ? 'text-white-50' : 'text-muted' }} text-truncate d-block"
                                            style="max-width: 160px;">
                                            {{ $conv->messages->first()?->body ?? 'لا توجد رسائل' }}
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8 col-lg-9 main-content d-flex flex-column">
                <div class="card shadow-sm flex-grow-1 d-flex flex-column">
                    <!-- Conversation Header -->
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ $otherUser->image_url }}" class="rounded-circle me-3" width="50" height="50"
                                alt="{{ $otherUser->fullName() }}">
                            <h5 class="mb-0">{{ $otherUser->fullName() }}</h5>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="card-body messages-container flex-grow-1 overflow-auto bg-white px-3 py-2" id="messages">
                        <div id="messagesWrapper">
                            @foreach ($messages as $message)
                                <div class="message d-flex {{ $message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-2"
                                    data-id="{{ $message->id }}">
                                    <div class="message-content p-3 rounded shadow
                                        {{ $message->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }}"
                                        style="max-width: 75%; word-wrap: break-word;">
                                        <p class="mb-1">{{ $message->body }}</p>
                                        <small
                                            class="{{ $message->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }} fst-italic"
                                            style="font-size: 0.75rem;">
                                            {{ $message->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Message Form -->
                    <div class="card-footer bg-light">
                        <form id="messageForm" action="{{ route('conversations.messages.store', $conversation) }}"
                            method="POST" autocomplete="off">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="body" id="messageInput" class="form-control"
                                    placeholder="اكتب رسالة..." required autocomplete="off">
                                <button class="btn btn-primary" type="submit" id="sendBtn">إرسال</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تقييم {{ $otherUser->fullName() }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('conversations.review.store', $conversation) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">التقييم</label>
                            <div class="rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating"
                                        value="{{ $i }}" required>
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تعليق (اختياري)</label>
                            <textarea name="comment" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .messages-container {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding-right: 0.5rem;
            display: flex;
            flex-direction: column;
        }

        #messagesWrapper {
            flex: 1;
            min-height: min-content;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .message {
            flex-shrink: 0;
        }

        /* Smooth scrolling */
        .messages-container {
            scroll-behavior: smooth;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const messagesContainer = $('#messages');
            const messagesWrapper = $('#messagesWrapper');
            const messageForm = $('#messageForm');
            const messageInput = $('#messageInput');
            const sendBtn = $('#sendBtn');
            let isLoading = false;
            let hasMoreMessages = true;
            let isInitialLoad = true;

            // Ensure CSRF token is set
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initial scroll to bottom
            setTimeout(scrollToBottom, 300);

            // Message form submission with AJAX
            messageForm.on('submit', function(e) {
                e.preventDefault();
                const message = messageInput.val().trim();
                if (!message) return;

                sendBtn.prop('disabled', true);
                const originalBtnText = sendBtn.html();
                sendBtn.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الإرسال...'
                );

                $.ajax({
                    url: messageForm.attr('action'),
                    method: 'POST',
                    data: {
                        body: message,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const newMessage = createMessageElement(response.message, true);
                            messagesWrapper.append(newMessage);
                            scrollToBottom();
                            messageInput.val('');
                            updateSidebarPreview(response.message.conversation_id, response
                                .message.body);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        alert('حدث خطأ أثناء إرسال الرسالة');
                    },
                    complete: function() {
                        sendBtn.prop('disabled', false);
                        sendBtn.html(originalBtnText);
                    }
                });
            });

            function scrollToBottom() {
                messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
            }

            function updateSidebarPreview(conversationId, messageText) {
                $(`a[href*="/conversations/${conversationId}"] small`).text(messageText);
            }

            function createMessageElement(message, isOwnMessage) {
                const messageClass = isOwnMessage ? 'justify-content-end' : 'justify-content-start';
                const bgClass = isOwnMessage ? 'bg-primary text-white' : 'bg-light text-dark';
                const timeClass = isOwnMessage ? 'text-white-50' : 'text-muted';

                return `
                <div class="message d-flex ${messageClass} mb-2" data-id="${message.id}">
                    <div class="message-content p-3 rounded shadow ${bgClass}" 
                         style="max-width: 75%; word-wrap: break-word;">
                        <p class="mb-1">${escapeHtml(message.body)}</p>
                        <small class="${timeClass} fst-italic" style="font-size: 0.75rem;">
                            ${formatMessageTime(message.created_at)}
                        </small>
                    </div>
                </div>`;
            }

            function escapeHtml(text) {
                return $('<div>').text(text).html();
            }

            function formatMessageTime(dateString) {
                if (!dateString) return 'الآن';

                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) return 'الآن';
                if (diffInSeconds < 3600) return `قبل ${Math.floor(diffInSeconds / 60)} دقيقة`;
                if (diffInSeconds < 86400) return `قبل ${Math.floor(diffInSeconds / 3600)} ساعة`;
                return `قبل ${Math.floor(diffInSeconds / 86400)} يوم`;
            }

            // Infinite scroll for older messages
            messagesContainer.on('scroll', function() {
                if (messagesContainer.scrollTop() < 100 && !isLoading && hasMoreMessages) {
                    loadOlderMessages();
                }
            });

            function loadOlderMessages() {
                const firstMessage = $('.message').first();
                const firstMessageId = firstMessage.data('id');
                if (!firstMessageId || isLoading) return;

                isLoading = true;
                const loadingIndicator = $('<div class="text-center py-2">جاري تحميل الرسائل القديمة...</div>');
                messagesWrapper.prepend(loadingIndicator);

                $.ajax({
                    url: '{{ route('conversations.show', $conversation) }}',
                    data: {
                        before: firstMessageId,
                        _ajax: true
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        loadingIndicator.remove();
                        if (response.html) {
                            const scrollPosition = messagesContainer.scrollTop();
                            const scrollHeight = messagesContainer[0].scrollHeight;

                            messagesWrapper.prepend(response.html);

                            const newScrollHeight = messagesContainer[0].scrollHeight;
                            messagesContainer.scrollTop(scrollPosition + (newScrollHeight -
                                scrollHeight));

                            hasMoreMessages = response.hasMore;
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading older messages:', xhr.responseText);
                    },
                    complete: function() {
                        isLoading = false;
                    }
                });
            }

            // Initialize Pusher if available
          function initializePusher() {
    if (window.Echo) {                      // <-- هون التعديل
      console.log('Subscribing…');
      window.Echo.private(`conversation.{{ $conversation->id }}`)
        .listen('ChatMessageSent', (data) => {
          console.log('Event:', data);
          if (data.message.user_id != '{{ auth()->id() }}') {
            const message = createMessageElement(data.message, false);
            $('#messagesWrapper').append(message);

            const messagesContainer = $('#messages');
            const isNearBottom = messagesContainer.scrollTop() + messagesContainer.innerHeight() >=
                                  messagesContainer[0].scrollHeight - 50;
            if (isNearBottom) {
              messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
            }
            updateSidebarPreview(data.message.conversation_id, data.message.body);
          }
        })
        .error((e) => console.error('Echo channel error:', e));
    } else {
      console.warn('window.Echo is not ready');
    }
  }

  // استدعاءها كما هو
  initializePusher();
        });
    </script>
@endpush
