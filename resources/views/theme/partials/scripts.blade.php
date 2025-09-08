<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Vendor JS Files -->
<!-- Vendor (CDN) — مؤقتاً بدل 404 -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<!-- Main JS (لو عندك ملف محلي ويحمّل) -->
<script defer src="{{ asset('assets/js/main.js') }}"></script>

<!-- SweetAlert -->
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Sweet alert (مع فحص اكتمال الملف) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.invitation-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const userName  = form.getAttribute('data-username') || 'هذا المستخدم';
      const formData  = new FormData(form);            // يحتوي message/description وكل الحقول
      const csrfToken = formData.get('_token');

      // 1) فحص الأهلية (تسجيل الدخول + اكتمال الملف)
      try {
        const res   = await fetch(`{{ route('invitations.check') }}`, {
          method: 'GET',
          headers: { 'X-Requested-With':'XMLHttpRequest' },
          credentials: 'same-origin'
        });
        const check = await res.json();

        if (check.status === 'unauthenticated') {
          // زائر: خليه يسجّل/يسجّل دخول
          Swal.fire({
            icon: 'warning',
            title: 'غير مسجّل',
            html: 'يرجى تسجيل الدخول أو إنشاء حساب لإرسال دعوة.',
            showCancelButton: true,
            confirmButtonText: 'تسجيل الدخول',
            cancelButtonText: 'إنشاء حساب'
          }).then(r=>{
            if (r.isConfirmed) {
              window.location.href = `{{ route('login') }}`;
            } else {
              // لو عندك مودال تسجيل للزوّار
              const m = document.getElementById('registerModal_users');
              if (m && typeof bootstrap !== 'undefined') new bootstrap.Modal(m).show();
              else window.location.href = `{{ route('register') }}`;
            }
          });
          return;
        }

        if (check.status === 'incomplete') {
          // ملف غير مكتمل
          Swal.fire({
            icon: 'info',
            title: 'الملف الشخصي غير مكتمل',
            html: `يجب إكمال ملفك الشخصي 100% قبل إرسال الدعوات.<br>
                   <small>نسبة الاكتمال الحالية: <b>${check.completion_percentage ?? 0}%</b></small><br>
                   <a href="{{ route('myProfile') }}" class="btn btn-primary mt-2">إكمال الملف الآن</a>`
          });
          return;
        }
      } catch (err) {
        // فشل التحقق: أوقف الإرسال بدل التخمين
        Swal.fire({ icon:'error', title:'تعذّر التحقق', text:'حاول مرة أخرى لاحقًا.' });
        return;
      }

      // 2) تأكيد ثم إرسال (نبقي الوصف/الرسالة كما هي ضمن formData)
      Swal.fire({
        title: 'هل أنت متأكد؟',
        html: `هل تريد إرسال دعوة إلى <strong>${userName}</strong>؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، أرسل الدعوة',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
      }).then((result) => {
        if (!result.isConfirmed) return;

        fetch(`{{ route('invitations.send') }}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        })
        .then(response => {
          if (!response.ok) throw new Error('حدث خطأ في الإرسال');
          return response.json();
        })
        .then(data => {
          Swal.fire({
            title: 'تم الإرسال!',
            text: data.message || 'تم إرسال الدعوة بنجاح.',
            icon: 'success',
            confirmButtonText: 'حسناً'
          });
        })
        .catch(error => {
          Swal.fire({
            title: 'خطأ!',
            text: error.message || 'تعذّر إرسال الدعوة.',
            icon: 'error',
            confirmButtonText: 'حسناً'
          });
        });
      });
    });
  });
});
</script>

{{-- Invitation Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const invitationButtons = document.querySelectorAll('.send-invitation-btn');
        if (!invitationButtons.length) return;

        $(document).on('click', '.send-invitation-btn', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const userName = form.data('user-name') || 'هذا المستخدم';
            const btn = $(this);

            form.attr('action', '{{ route('invitations.send') }}');

            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status"></span>
                جاري التحقق...
            `);

            checkInvitationEligibility()
                .then(response => {
                    btn.prop('disabled', false).text('دعوة');

                    if (response.status === 'unauthenticated') {
                        showError('يجب تسجيل الدخول أولاً لإرسال دعوات');
                    } else if (response.status === 'incomplete') {
                        showProfileIncompleteWarning(response.completion_percentage);
                    } else {
                        Swal.fire({
                            title: 'إرسال دعوة',
                            html: `هل تريد إرسال دعوة إلى <strong>${userName}</strong>؟`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'نعم، أرسل الدعوة',
                            cancelButtonText: 'إلغاء',
                            confirmButtonColor: '#4e73df',
                            cancelButtonColor: '#d33',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                sendInvitation(form);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Eligibility check error:', error);
                    btn.prop('disabled', false).text('دعوة');
                    showError('حدث خطأ أثناء التحقق من الأهلية');
                });
        });

        function checkInvitationEligibility() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('invitations.check') }}',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Eligibility check failed:', status, error);
                        reject(error);
                    }
                });
            });
        }

        function showError(message) {
            Swal.fire({
                title: 'خطأ',
                text: message,
                icon: 'error',
                confirmButtonColor: '#4e73df',
                confirmButtonText: 'حسناً'
            });
        }

        function showProfileIncompleteWarning(percentage) {
            Swal.fire({
                title: 'ملف غير مكتمل',
                html: `يجب إكمال ملفك الشخصي بنسبة 100% قبل إرسال الدعوات.<br>
                      <small>إكتمال الملف الحالي: ${percentage}%</small><br>
                      <a href="{{ route('myProfile') }}" class="btn btn-primary mt-2">الذهاب إلى الملف الشخصي</a>`,
                icon: 'warning',
                confirmButtonColor: '#4e73df'
            });
        }

        function sendInvitation(form) {
            const btn = form.find('button');
            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status"></span>
                جاري الإرسال...
            `);

            const requestData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                destination_user_id: form.find('input[name="destination_user_id"]').val()
            };

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: requestData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    btn.prop('disabled', false).text('دعوة مرسلة');
                    Swal.fire({
                        title: 'تم بنجاح',
                        text: response.message || 'تم إرسال الدعوة بنجاح!',
                        icon: 'success',
                        confirmButtonColor: '#4e73df',
                        confirmButtonText: 'حسناً'
                    });
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('دعوة');
                    let errorMessage = 'حدث خطأ أثناء إرسال الدعوة';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showError(errorMessage);
                }
            });
        }
    });
</script>

{{-- Invitation Reply Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const replyButtons = document.querySelectorAll('.reply-btn');
        if (!replyButtons.length) return;

        $(document).on('click', '.reply-btn', function() {
            const button = $(this);
            const url = button.data('url');
            const reply = button.data('reply');

            Swal.fire({
                title: 'تأكيد الرد',
                text: `هل أنت متأكد من ${reply} الدعوة؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `نعم، ${reply}`,
                cancelButtonText: 'إلغاء',
                confirmButtonColor: reply === 'قبول' ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    sendReply(url, reply, button);
                }
            });
        });

        function sendReply(url, reply, button) {
            const originalText = button.text();
            button.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status"></span>
                جاري الإرسال...
            `);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    reply: reply
                },
                success: function(response) {
                    Swal.fire({
                        title: 'تم بنجاح',
                        text: response.message || 'تم تحديث حالة الدعوة',
                        icon: 'success',
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload(); // Refresh to show updated status
                    });
                },
                error: function(xhr) {
                    button.prop('disabled', false).text(originalText);
                    let errorMessage = 'حدث خطأ أثناء إرسال الرد';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'خطأ',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
</script>

<script>
    function validateInvitationEligibility() {
        fetch('{{ route('invitations.check') }}')
            .then(response => {
                if (!response.ok) throw response;
                return response.json();
            })
            .then(data => {
                // User is authenticated and complete
                window.location.href = '{{ route('invitations.index') }}';
            })
            .catch(async error => {
                if (error.status === 401) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'غير مسجل',
                        text: 'يرجى تسجيل الدخول أولاً.',
                        confirmButtonText: 'تسجيل الدخول'
                    }).then(() => {
                        window.location.href = '{{ route('login') }}';
                    });
                } else if (error.status === 403) {
                    Swal.fire({
                        icon: 'info',
                        title: 'الملف الشخصي غير مكتمل',
                        text: 'يرجى إكمال معلوماتك الشخصية قبل إرسال دعوة.',
                        confirmButtonText: 'إكمال الملف'
                    }).then(() => {
                        window.location.href = '{{ route('myProfile') }}';
                    });
                }
            });
    }
</script>

{{-- Hero Search Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const heroSearchForm = document.getElementById('heroSearchForm');

        if (heroSearchForm) {
            heroSearchForm.addEventListener('submit', function(e) {
                // The default form submission will handle the navigation
                // No need for additional JavaScript unless you want AJAX
            });

            const searchInput = heroSearchForm.querySelector('input[name="search"]');
            if (searchInput) {
            }
        }
    });
</script>

<script>
    if (document.querySelector('.invite-btn') || document.getElementById('users-container')) {
        document.addEventListener('DOMContentLoaded', function() {

            const usersContainer = document.getElementById('users-container');
            if (!usersContainer) return;

            function initializeInvitationForms() {
                const inviteButtons = document.querySelectorAll('.invite-btn');
                if (!inviteButtons.length) return;

                inviteButtons.forEach(btn => {
                    btn.removeEventListener('click', handleInviteClick);
                    btn.addEventListener('click', handleInviteClick);
                });
            }

            function handleInviteClick(e) {
                e.preventDefault();
                const userId = e.currentTarget.getAttribute('data-user-id');

                Swal.fire({
                    title: 'إرسال دعوة',
                    text: `هل تريد دعوة المستخدم ${userId}؟`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، أرسل الدعوة',
                    cancelButtonText: 'إلغاء'
                });
            }

            initializeInvitationForms();

            document.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (!link) return;

                const usersContainer = document.getElementById('users-container');
                if (!usersContainer) return;

                e.preventDefault();
                const url = link.getAttribute('href');

                usersContainer.innerHTML =
                    '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) usersContainer.innerHTML = data.html;
                        if (data.pagination) {
                            const paginationContainer = document.getElementById('pagination-links');
                            if (paginationContainer) paginationContainer.innerHTML = data
                                .pagination;
                        }
                        initializeInvitationForms();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (usersContainer) {
                            usersContainer.innerHTML =
                                '<div class="col-12 text-center py-5"><h4>حدث خطأ أثناء جلب البيانات</h4></div>';
                        }
                    });
            });
        });
    }
</script>

<script>
(function(){
  const form     = document.getElementById('filterForm');
  const root     = document.getElementById('results-root');
  const usersBox = document.getElementById('users-container');
  const pagBox   = document.getElementById('pagination-links');
  if (!form || !root || !usersBox || !pagBox) return;

  const HEADERS = { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' };

  function setLoading(on){ root.classList.toggle('loading', !!on); }

  async function fetchPage(url, pushState){
    setLoading(true);
    try{
      const res = await fetch(url, { headers: HEADERS, credentials: 'same-origin' });
      const ct  = res.headers.get('Content-Type') || '';
      // لو رجع HTML (سيشن/خطأ) افتح الصفحة كاملة
      if (!ct.includes('application/json')) {
        window.location.href = url.replace(/([?&])partial=1(&|$)/,'$1').replace(/[?&]$/,'');
        return;
      }
      const data = await res.json();
      if (!data?.ok) {
        window.location.href = url.replace(/([?&])partial=1(&|$)/,'$1').replace(/[?&]$/,'');
        return;
      }
      if (data.users_html)      usersBox.innerHTML = data.users_html;
      if (data.pagination_html) pagBox.innerHTML   = data.pagination_html;

      const clean = (data.url || url).replace(/([?&])partial=1(&|$)/,'$1').replace(/[?&]$/,'');
      pushState ? history.pushState(null,'', clean) : history.replaceState(null,'', clean);
    } finally { setLoading(false); }
  }

  // هاندلر الباجينيشن: نقيّده على #pagination-links ونوقّف أي handlers ثانية
  document.addEventListener('click', function(ev){
    const a = ev.target.closest('#pagination-links a');
    if (!a) return;

    ev.preventDefault();
    ev.stopPropagation();
    if (typeof ev.stopImmediatePropagation === 'function') ev.stopImmediatePropagation();

    const u = new URL(a.href, window.location.href);
    u.searchParams.set('partial','1');
    fetchPage(u.toString(), true);
  });

  // دعم زر الرجوع/التقدّم
  window.addEventListener('popstate', function(){
    const u = new URL(window.location.href);
    u.searchParams.set('partial','1');
    fetchPage(u.toString(), false);
  });
})();
</script>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
<script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function() {
        OneSignal.init({
            appId: '{{ config('services.onesignal.app_id') }}',
            notifyButton: {
                enable: true
            },
            allowLocalhostAsSecureOrigin: true
        });

        @auth
        OneSignal.on('subscriptionChange', function(isSubscribed) {
            if (isSubscribed) {
                OneSignal.getUserId(function(playerId) {
                    fetch("{{ route('onesignal.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            player_id: playerId
                        })
                    }).catch(err => console.error('Failed to update player ID:', err));
                });
            }
        });
    @endauth
    });
</script>

<script>
    function updateInvitationCount() {
        fetch('{{ url('/invitations/count') }}')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('invitation-count');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(err => console.error('Failed to fetch invitation count:', err));
    }

    @auth
    updateInvitationCount();
    setInterval(updateInvitationCount, 15000);
    @endauth
</script>
