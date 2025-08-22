@extends('theme.master')

@section('content')
    <section class="section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4 p-sm-5">
                            <h2 class="card-title text-center mb-4">تسجيل الدخول</h2>

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">تذكرني</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
                                </div>

                                <div class="text-center mt-3">
                                    <p>أو</p>
                                    <a href="{{ route('google.login') }}" class="btn btn-outline-danger" id="google-anchor">
                                        <i class="bi bi-google me-2"></i> تسجيل الدخول باستخدام جوجل
                                    </a>
                                </div>

                                <div class="text-center mt-3">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                                    @endif
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <p class="mb-0">ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
