<form id="qualificationsForm"
      method="POST"
      action="{{ route('profile.update-qualifications') }}"
      class="mb-4">
  @csrf
  @method('PUT')

  {{-- رسالة الاستجابة --}}
  <div id="qualificationsAlert" class="alert d-none" role="alert"></div>

  <div class="card hover-lift">
    <div class="card-header">
      <h6 class="m-0 fw-bold">{{ __('profile.qualifications.description_title') }}</h6>
    </div>

    <div class="card-body col-lg-10 mx-auto">
      @if ($user->skills->count() > 0)
        <ul class="list-group list-group-flush">
          @foreach ($user->skills as $skill)
            @php $sid = (int) $skill->id; @endphp
            <li class="list-group-item border-0 px-0">
              <div class="mb-3">
                <label class="form-label fw-bold" for="skill-desc-{{ $sid }}">
                  {{ $skill->name }}
                </label>

                <textarea
                  id="skill-desc-{{ $sid }}"
                  class="form-control skill-description"
                  name="skills[{{ $sid }}][description]"
                  rows="4"
                  maxlength="500"
                  placeholder="{{ __('profile.qualifications.placeholder') }}">{{ $skill->pivot->description ?? '' }}</textarea>

                {{-- عداد الأحرف + ملاحظات --}}
                <div class="d-flex justify-content-between mt-1">
                  <small class="text-muted">
                    {{ __('common.optional') ?? 'اختياري' }}
                  </small>
                  <small class="text-muted char-count" data-for="skill-desc-{{ $sid }}">
                    <span class="count">0</span>/500
                  </small>
                </div>

                {{-- خطأ خاص بهذا الحقل (يمتلئ عبر الـ JS عند 422) --}}
                <div class="invalid-feedback d-block d-none" id="error-skill-{{ $sid }}"></div>
              </div>
            </li>
          @endforeach
        </ul>
      @else
        <div class="alert alert-info mb-0">{{ __('profile.qualifications.empty') }}</div>
      @endif
    </div>
  </div>

  @if ($user->skills->count() > 0)
    <div class="text-center mt-3">
      <button type="submit" class="btn btn-primary btn-lg hover-lift" id="qualificationsSubmitBtn">
        <span class="btn-text">
          <i class="fas fa-save me-1"></i>{{ __('profile.btn.save_changes') }}
        </span>
        <span class="spinner-border spinner-border-sm d-none align-middle" role="status" aria-hidden="true"></span>
      </button>
    </div>
  @endif
</form>
