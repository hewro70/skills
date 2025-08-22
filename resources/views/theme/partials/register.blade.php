@extends('theme.master')

@section('content')
    <section class="section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4 p-sm-5">
                            <h2 class="card-title text-center mb-4">إنشاء حساب جديد</h2>

                            <form action="{{ route('register') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback d-block text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback d-block text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password-confirm" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password-confirm" name="password_confirmation" required>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">تسجيل</button>
                                </div>

                                <div class="text-center mt-3">
                                    <p>أو</p>
                                    {{-- <a href="{{ route('google.login') }}" class="btn btn-outline-danger">
                                        <i class="bi bi-google me-2 text-primary"></i> تسجيل الدخول باستخدام جوجل
                                    </a> --}}

                                    <a href="{{ route('google.login') }}" class="btn btn-google-blue" id="google-anchor">
                                        <i class="bi bi-google me-2"></i> تسجيل الدخول باستخدام جوجل
                                    </a>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <p class="mb-0">لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
