@extends('theme.master')
@section('contact-active', 'active')

@section('content')
    <main class="main">

        @include('theme.partials.heroSection', [
            'title' => 'تواصل معنا',
            'description' => 'تواصل معنا لمعرفة المزيد',
            'current' => 'تواصل معنا',
        ])

        <!-- Contact Section -->
        <section id="contact" class="contact section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-between align-items-start">

                    <!-- Info Section -->
                    <div class="col-lg-4">
                        <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="500">
                            <i class="bi bi-building flex-shrink-0 me-3"></i>
                            <div>
                                <h3>مهارات هب</h3>
                                <p>******************</p>
                            </div>
                        </div>

                        <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-envelope flex-shrink-0 me-3"></i>
                            <div>
                                <h3>بريدنا الالكتروني</h3>
                                <p>info@maharathub.com</p>
                            </div>
                        </div>

                        <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-telephone flex-shrink-0 me-3"></i>
                            <div>
                                <h3>اتصل بنا</h3>
                                <p>**********</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="col-lg-8">

                        <form action="{{ route('contact.submit') }}" method="POST" class="p-4"
                            style="border: 1px solid #333; border-radius: 0.25rem;">
                            @csrf

                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" name="first_name" class="form-control" placeholder="الاسم الأول"
                                        value="{{ old('first_name') }}" required>
                                    <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="last_name" class="form-control" placeholder="الاسم الأخير"
                                        value="{{ old('last_name') }}" required>
                                    <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
                                </div>

                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="البريد الإلكتروني" value="{{ old('email') }}" required>
                                    <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="phone" class="form-control" placeholder="رقم الهاتف"
                                        value="{{ old('phone') }}">
                                    <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
                                </div>

                                <div class="col-md-12">
                                    <select name="service" class="form-control" required>
                                        <option value="" selected disabled>اختر الخدمة</option>
                                        <option value="collaboration"
                                            {{ old('service') == 'collaboration' ? 'selected' : '' }}>تعاون معنا</option>
                                        <option value="Complaints" {{ old('service') == 'Complaints' ? 'selected' : '' }}>
                                            شكاوى</option>
                                        <option value="note" {{ old('service') == 'note' ? 'selected' : '' }}>اقتراحات
                                        </option>
                                    </select>
                                    <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="الرسالة..." required>{{ old('message') }}</textarea>
                                    <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
                                </div>

                                <div class="col-md-12 text-center">
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary">إرسال</button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </section><!-- /Contact Section -->

    </main>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Show validation error under the input field
            function showValidationError(input, message) {
                const errorContainer = input.closest('.form-group').find('.field-errors');
                errorContainer.empty().append(`<li>${message}</li>`).show();
            }

            // Clear validation error under the input field
            function clearValidationError(input) {
                const errorContainer = input.closest('.form-group').find('.field-errors');
                errorContainer.empty().hide();
            }

            $('form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');

                // Clear all previous errors
                form.find('.field-errors').empty().hide();

                let isValid = true;
                let firstErrorField = null;

                // Required field validation
                form.find('[required]').each(function() {
                    const input = $(this);
                    const value = input.val().trim();
                    if (!value) {
                        const fieldName = input.attr('name');
                        const displayName = {
                            'first_name': 'الاسم الأول',
                            'last_name': 'الاسم الأخير',
                            'email': 'البريد الإلكتروني',
                            'service': 'الخدمة',
                            'message': 'الرسالة'
                        } [fieldName] || fieldName;

                        showValidationError(input, `حقل ${displayName} مطلوب`);
                        if (!firstErrorField) firstErrorField = input;
                        isValid = false;
                    }
                });

                // Email format validation
                const emailInput = form.find('[name="email"]');
                const emailVal = emailInput.val().trim();
                if (emailVal && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
                    showValidationError(emailInput, 'البريد الإلكتروني غير صالح');
                    if (!firstErrorField) firstErrorField = emailInput;
                    isValid = false;
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'بيانات ناقصة',
                        text: 'الرجاء إكمال جميع الحقول المطلوبة',
                        confirmButtonText: 'حسناً'
                    });

                    if (firstErrorField) {
                        $('html, body').animate({
                            scrollTop: firstErrorField.offset().top - 100
                        }, 500);
                    }

                    return;
                }

                submitBtn.prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    جاري الإرسال...
                `);

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('إرسال');

                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح',
                            text: response?.message ?? 'تم إرسال رسالتك بنجاح',
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            form[0].reset();
                            form.find('.field-errors').empty().hide();
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('إرسال');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                const input = form.find(`[name="${field}"]`);
                                if (input.length) {
                                    const errorContainer = input.closest('.form-group').find(
                                        '.field-errors');
                                    errorContainer.empty();
                                    errors[field].forEach(msg => {
                                        errorContainer.append(`<li>${msg}</li>`).show();
                                    });
                                }
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في البيانات',
                                text: 'الرجاء تصحيح الأخطاء في النموذج',
                                confirmButtonText: 'حسناً'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ غير متوقع. يرجى المحاولة لاحقاً.',
                                confirmButtonText: 'موافق'
                            });
                        }
                    }
                });
            });

            // Real-time input validation clearing
            $('form [required]').on('input blur', function() {
                const input = $(this);
                clearValidationError(input);

                // Live validation for email field
                if (input.attr('name') === 'email' && input.val().trim()) {
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.val())) {
                        showValidationError(input, 'البريد الإلكتروني غير صالح');
                    }
                }
            });
        });
    </script>

    <style>
        .field-errors {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .field-errors li {
            list-style-type: none;
        }
    </style>
@endsection
