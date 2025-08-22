@extends('layout.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>تعديل الملف الشخصي</h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- الاسم --}}
                        <div class="mb-3">
                            <label for="first_name" class="form-label">الاسم</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" 
                                   value="{{ old('first_name', $user->first_name) }}" required>
                            <div class="invalid-feedback">يرجى إدخال الاسم.</div>
                            @error('first_name')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- البريد الالكتروني --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="{{ old('email', $user->email) }}" required>
                            <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صالح.</div>
                            @error('email')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- الصورة الشخصية الحالية --}}
                        @if($user->image_path)
                            <div class="mb-3 text-center">
                                <label class="form-label d-block">الصورة الحالية</label>
                                <img src="{{ asset('storage/' . $user->image_path) }}" 
                                     alt="الصورة الشخصية" 
                                     class="img-thumbnail shadow-sm rounded-circle" 
                                     width="120" height="120" 
                                     style="object-fit: cover;">
                            </div>
                        @endif

                        {{-- رفع صورة جديدة --}}
                        <div class="mb-3">
                            <label for="image_path" class="form-label">صورة جديدة</label>
                            <input type="file" name="image_path" id="image_path" class="form-control">
                            @error('image_path')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> حفظ التعديلات
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <footer class="text-center text-muted py-3 mt-5 border-top">
                © {{ date('Y') }} جميع الحقوق محفوظة - Maharat Hub
            </footer>
        </div>
    </div>
</div>

{{-- Bootstrap validation --}}
<script>
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})();
</script>
@endsection
