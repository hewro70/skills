@extends('layout.admin')

@push('styles')
    {{-- Bootstrap 5 + Icons (CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* تحسين بسيط للأزرار والجدول والصفحات */
        .table thead th { white-space: nowrap; }
        .pagination { margin-bottom: 0; }
        .btn-outline-primary, .btn-outline-danger { min-width: 36px; }
    </style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0"><i class="bi bi-tags-fill me-2"></i> إدارة التصنيفات والمهارات</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">تحقق من الأخطاء التالية:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- إضافة تصنيف / إضافة مهارة --}}
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-folder-plus me-2"></i>إضافة تصنيف جديد</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.classifications.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">الاسم (عربي)</label>
                            <input type="text" name="name[ar]" class="form-control" placeholder="مثال: برمجة" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الاسم (إنجليزي)</label>
                            <input type="text" name="name[en]" class="form-control" placeholder="e.g., Programming" required>
                        </div>
                        <button class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> إضافة
                        </button>
                    </form>
                </div>
            </div>
        </div><!-- col -->

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-plus-square-dotted me-2"></i>إضافة مهارة جديدة</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.skills.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">التصنيف</label>
               <select name="classification_id" class="form-select" required>
  <option value="">-- اختر التصنيف --</option>
  @foreach($classificationOptions as $c)
    <option value="{{ $c->id }}" @selected(old('classification_id') == $c->id)>
      {{ $c->getTranslation('name', app()->getLocale()) }}
    </option>
  @endforeach
</select>

                        </div>
                        <div class="mb-3">
                            <label class="form-label">الاسم (عربي)</label>
                            <input type="text" name="name[ar]" class="form-control" placeholder="مثال: Laravel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الاسم (إنجليزي)</label>
                            <input type="text" name="name[en]" class="form-control" placeholder="e.g., Laravel" required>
                        </div>
                        <button class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> إضافة
                        </button>
                    </form>
                </div>
            </div>
        </div><!-- col -->
    </div><!-- row -->

    <hr class="my-4">

    {{-- جدول التصنيفات --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="bi bi-folder2-open me-2"></i>التصنيفات</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px">#</th>
                            <th>الاسم (عربي)</th>
                            <th>الاسم (إنجليزي)</th>
                            <th style="width: 180px">إجراءات</th>
                        </tr>
                    </thead>
                   <tbody>
@forelse($classifications as $c)
  <tr>
    <td>{{ $c->id }}</td>
    <td>{{ $c->getTranslation('name','ar') }}</td>
    <td>{{ $c->getTranslation('name','en') }}</td>
    <td>
      <!-- زر يفتح مودال خاص بهذا الصف -->
      <button class="btn btn-sm btn-outline-primary"
              data-bs-toggle="modal"
              data-bs-target="#editClassificationModal-{{ $c->id }}">
        <i class="bi bi-pencil-square"></i>
      </button>

      <form action="{{ route('admin.classifications.destroy', $c) }}"
            method="POST" class="d-inline"
            onsubmit="return confirm('تأكيد حذف التصنيف؟');">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger">
          <i class="bi bi-trash3"></i>
        </button>
      </form>
    </td>
  </tr>

  <!-- مودال التعديل الخاص بهذا التصنيف -->
  <div class="modal fade" id="editClassificationModal-{{ $c->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('admin.classifications.update', $c) }}">
        @csrf @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">تعديل تصنيف</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">الاسم (عربي)</label>
              <input type="text" name="name[ar]" class="form-control"
                     value="{{ $c->getTranslation('name','ar') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">الاسم (إنجليزي)</label>
              <input type="text" name="name[en]" class="form-control"
                     value="{{ $c->getTranslation('name','en') }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i> حفظ</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@empty
  <tr><td colspan="4" class="text-center text-muted">لا توجد تصنيفات.</td></tr>
@endforelse
</tbody>

                </table>
            </div>

            <div class="p-3">
                {{-- إجبار الـ pagination على Bootstrap 5 --}}
                {{ $classifications->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- جدول المهارات --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="bi bi-lightning-charge-fill me-2"></i>المهارات</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px">#</th>
                            <th>التصنيف</th>
                            <th>الاسم (عربي)</th>
                            <th>الاسم (إنجليزي)</th>
                            <th style="width: 220px">إجراءات</th>
                        </tr>
                    </thead>
                  <tbody>
@forelse($skills as $s)
  <tr>
    <td>{{ $s->id }}</td>
    <td>{{ optional($s->classification)->getTranslation('name', app()->getLocale()) ?? '—' }}</td>
    <td>{{ $s->getTranslation('name','ar') }}</td>
    <td>{{ $s->getTranslation('name','en') }}</td>
    <td>
      <button class="btn btn-sm btn-outline-primary"
              data-bs-toggle="modal"
              data-bs-target="#editSkillModal-{{ $s->id }}">
        <i class="bi bi-pencil-square"></i>
      </button>

      <form action="{{ route('admin.skills.destroy', $s) }}"
            method="POST" class="d-inline"
            onsubmit="return confirm('تأكيد حذف المهارة؟');">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger">
          <i class="bi bi-trash3"></i>
        </button>
      </form>
    </td>
  </tr>

  <!-- مودال التعديل الخاص بهذه المهارة -->
  <div class="modal fade" id="editSkillModal-{{ $s->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('admin.skills.update', $s) }}">
        @csrf @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">تعديل مهارة</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">التصنيف</label>
           <select name="classification_id" class="form-select" required>
  <option value="">-- اختر التصنيف --</option>
  @foreach($classificationOptions as $c)
    <option value="{{ $c->id }}" @selected(old('classification_id') == $c->id)>
      {{ $c->getTranslation('name', app()->getLocale()) }}
    </option>
  @endforeach
</select>

            </div>
            <div class="mb-3">
              <label class="form-label">الاسم (عربي)</label>
              <input type="text" name="name[ar]" class="form-control"
                     value="{{ $s->getTranslation('name','ar') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">الاسم (إنجليزي)</label>
              <input type="text" name="name[en]" class="form-control"
                     value="{{ $s->getTranslation('name','en') }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i> حفظ</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@empty
  <tr><td colspan="5" class="text-center text-muted">لا توجد مهارات.</td></tr>
@endforelse
</tbody>

                </table>
            </div>

            <div class="p-3">
                {{ $skills->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
<!-- Edit Classification -->
<div class="modal fade" id="editClassificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editClassificationForm" action="#">
        @csrf @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل تصنيف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">الاسم (عربي)</label>
                    <input type="text" name="name[ar]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">الاسم (إنجليزي)</label>
                    <input type="text" name="name[en]" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"><i class="bi bi-save me-1"></i> حفظ</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Edit Skill -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editSkillForm" action="#">
        @csrf @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل مهارة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">التصنيف</label>
                    <select name="classification_id" class="form-select" required>
                        <option value="">-- اختر التصنيف --</option>
                        @foreach($classifications as $c)
                            <option value="{{ $c->id }}">{{ $c->getTranslation('name', app()->getLocale()) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">الاسم (عربي)</label>
                    <input type="text" name="name[ar]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">الاسم (إنجليزي)</label>
                    <input type="text" name="name[en]" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"><i class="bi bi-save me-1"></i> حفظ</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
    {{-- Bootstrap 5 Bundle (يتضمن Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // تصحيح الإجراء عند فتح مودال "التصنيف"
        const editClassificationModal = document.getElementById('editClassificationModal');
        editClassificationModal?.addEventListener('show.bs.modal', function (event) {
            const btn  = event.relatedTarget;
            const form = document.getElementById('editClassificationForm');

            form.action = btn.getAttribute('data-update-url'); // 👈 URL النهائي من الزر
            form.querySelector('input[name="name[ar]"]').value = btn.getAttribute('data-name-ar') || '';
            form.querySelector('input[name="name[en]"]').value = btn.getAttribute('data-name-en') || '';
        });

        // تصحيح الإجراء عند فتح مودال "المهارة"
        const editSkillModal = document.getElementById('editSkillModal');
        editSkillModal?.addEventListener('show.bs.modal', function (event) {
            const btn  = event.relatedTarget;
            const form = document.getElementById('editSkillForm');

            form.action = btn.getAttribute('data-update-url'); // 👈 URL النهائي من الزر
            form.querySelector('select[name="classification_id"]').value = btn.getAttribute('data-classification-id') || '';
            form.querySelector('input[name="name[ar]"]').value = btn.getAttribute('data-name-ar') || '';
            form.querySelector('input[name="name[en]"]').value = btn.getAttribute('data-name-en') || '';
        });
    });
    </script>
@endpush
