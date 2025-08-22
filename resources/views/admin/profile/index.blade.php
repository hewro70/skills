@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <div class="card mb-5 shadow-sm rounded-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">ملفي الشخصي</h5>
            <a class="btn btn-outline-primary btn-sm" type="button"  href="{{route('profile.edit')}}">
                <i class="bi bi-pencil-square me-1"></i> تعديل
            </a>
        </div>

        <div class="card-body">

          

            <ul class="list-group list-group-flush mb-4 shadow-sm rounded-3">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>الاسم</strong>
                    <span class="text-secondary">{{ $user->first_name}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>البريد الإلكتروني</strong>
                    <span class="text-secondary">{{ $user->email }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>تاريخ الإنشاء</strong>
                    <span class="text-secondary">{{ $user->created_at->format('Y-m-d') }}</span>
                </li>
               
            </ul>

            <!-- نموذج التعديل داخل Collapse -->
         
        </div>
    </div>

    <footer class="text-center text-muted py-3 border-top mt-5">
        © {{ date('Y') }} جميع الحقوق محفوظة - Maharat Hub
    </footer>

</div>

{{-- Validation script --}}
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
