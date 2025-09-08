@extends('theme.master')

@section('content')
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">بدء محادثة جديدة</h4>
            </div>
            <div class="card-body">
                <h5 class="mb-3">اختر شخصاً للدردشة معه:</h5>

                <div class="list-group">
                    @foreach ($invitations as $invitation)
                        @php $user = $invitation->sourceUser; @endphp
                        @if (!$user)
                            @continue
                        @endif

                        <form action="{{ route('conversations.store') }}" method="POST" class="conversation-form mb-3"
                            data-user-id="{{ $user->id }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="list-group-item d-flex align-items-start">
                                <img src="{{ $user->getImageUrlAttribute() }}" class="rounded-circle me-3" width="50" height="50"
                                    alt="{{ $user->fullName() }}">

                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $user->fullName() }}</h6>
                                    <small class="text-muted">قبلت دعوتك في
                                        {{ $invitation->updated_at->diffForHumans() }}</small>

                                    <textarea name="message" class="form-control mt-2" rows="2" placeholder="اكتب رسالتك هنا..." required></textarea>

                                    <button type="submit" class="btn btn-primary btn-sm mt-2 send-message-btn">
                                        <span class="btn-text">إرسال</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
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
@endsection

@push('styles')
<style>
  :root{
    --primary:#4e73df;
    --primary-600:#3f63d8;
    --primary-700:#2e59d9;
    --surface:#ffffff;
    --bg:#f6f8fc;
    --text:#0f172a;
    --muted:#6b7280;
    --card-shadow:0 10px 25px rgba(17,24,39,.06), 0 2px 8px rgba(17,24,39,.04);
    --radius:16px;
  }

  body{ background:linear-gradient(180deg,#f8faff 0%, #f6f8fc 100%); }

  /* ====== Page container ====== */
  .container.py-4{ padding-top:1.25rem !important; padding-bottom:1.25rem !important; }

  /* ====== Card ====== */
  .card{
    border:0;
    border-radius:var(--radius);
    box-shadow:var(--card-shadow);
    overflow:hidden;
    background:var(--surface);
  }
  .card-header{
    border:0;
    padding:1rem 1.25rem;
    background:linear-gradient(135deg,var(--primary) 0%, var(--primary-700) 100%) !important;
    color:#fff;
  }
  .card-header h4{ margin:0; font-weight:700; letter-spacing:.2px; }

  .card-body{
    background:
      radial-gradient(80% 50% at 10% 0%, rgba(78,115,223,.05), transparent 60%),
      radial-gradient(80% 60% at 100% 10%, rgba(62,86,214,.05), transparent 60%),
      #fff;
    padding:1.25rem;
  }

  /* ====== List / items ====== */
  .list-group{ --bs-list-group-bg: transparent; }
  .list-group-item{
    border:0 !important;
    border-radius:14px;
    padding:1rem;
    background:#fff;
    box-shadow:0 4px 14px rgba(17,24,39,.05);
    transition: transform .12s ease, box-shadow .2s ease, background .2s ease;
  }
  .list-group-item + .list-group-item{ margin-top:.75rem; }
  .list-group-item:hover{
    transform: translateY(-1px);
    box-shadow:0 10px 24px rgba(17,24,39,.08);
    background:#f9fbff;
  }

  /* Avatar + titles */
  .list-group-item .rounded-circle{
    box-shadow:0 2px 8px rgba(0,0,0,.08);
    outline:3px solid rgba(78,115,223,.08);
  }
  .list-group-item h6{
    margin:0 0 .15rem;
    font-weight:700;
    color:var(--text);
  }
  .list-group-item small{ color:var(--muted) !important; }

  /* ====== Textarea ====== */
  .list-group-item textarea.form-control{
    border-radius:12px;
    border:1px solid #e4e8f3;
    background:#fff;
    transition:border .15s ease, box-shadow .15s ease, background .2s ease;
    resize: vertical;
    min-height: 72px;
  }
  .list-group-item textarea.form-control:focus{
    border-color:#c9d6ff;
    box-shadow:0 0 0 .25rem rgba(78,115,223,.15);

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.conversation-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = form.querySelector('.send-message-btn');
                    const spinner = submitBtn.querySelector('.spinner-border');
                    const btnText = submitBtn.querySelector('.btn-text');
                    const errorMessage = form.querySelector('.error-message');

                    errorMessage.classList.add('d-none');
                    errorMessage.textContent = '';
                    submitBtn.disabled = true;
                    btnText.textContent = 'جاري الإرسال...';
                    spinner.classList.remove('d-none');

                    fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]')
                                    .value,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.error ||
                                        'حدث خطأ أثناء الإرسال');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'تم إرسال الرسالة بنجاح',
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                // Clear the message textarea
                                form.querySelector('textarea[name="message"]').value = '';

                                // Reload the conversation list via AJAX
                                loadConversations();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            errorMessage.textContent = error.message;
                            errorMessage.classList.remove('d-none');

                            Swal.fire({
                                title: 'خطأ!',
                                text: error.message,
                                icon: 'error',
                                confirmButtonText: 'حسناً'
                            });
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            btnText.textContent = 'إرسال';
                            spinner.classList.add('d-none');
                        });
                });
            });

            // Function to load conversations via AJAX
            function loadConversations() {
                fetch('{{ route('conversations.index') }}?_ajax=1', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('.list-group').innerHTML = html;
                    })
                    .catch(error => console.error('Error loading conversations:', error));
            }
        });
    </script>
@endpush
