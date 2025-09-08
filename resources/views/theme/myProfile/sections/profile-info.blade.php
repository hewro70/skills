<div class="row g-3">
  <div class="col-12 col-xl-3">
    <div class="card hover-lift">
      <div class="card-body text-center">
        <img id="profileImage" src="{{ $user->image_url }}" class="rounded-circle profile-img mb-3" alt="{{ __('profile.image.alt') }}">
        <h3 class="fw-bold mb-1">{{ $user->fullName() }}</h3>
        <p class="text-muted mb-3">{{ $user->email }}</p>
@if($user->is_mentor)
  <p class="mb-3">
    <span class="badge bg-success">
      <i class="fas fa-chalkboard-teacher me-1"></i> {{ __('profile.role.mentor') ?? 'Mentor / معلم' }}
    </span>
  </p>
@endif
        <input type="file" id="profileImageInput" accept="image/jpeg, image/png, image/gif, image/webp" hidden>
        <div class="d-grid gap-2">
          <button class="btn btn-primary hover-lift" id="uploadImageBtn">
            <i class="fas fa-camera me-1"></i>{{ __('profile.image.change') }}
          </button>

          @if ($user->image_path)
            <button class="btn btn-outline-danger hover-lift" id="removeImageBtn">
              <i class="fas fa-trash me-1"></i>{{ __('profile.image.remove') }}
            </button>
          @endif
        </div>
      </div>
    </div>

    <div class="card hover-lift mt-3">
      <div class="card-body">
        <h6 class="mb-3 fw-bold">{{ __('profile.completion.title') }}</h6>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">{{ __('profile.completion.progress') }}</span>
          <span class="text-primary fw-bold">{{ $user->profileCompletionPercentage() }}%</span>
        </div>
        <div class="progress" style="height:10px;">
          <div class="progress-bar bg-primary" role="progressbar"
               style="width: {{ $user->profileCompletionPercentage() }}%"
               aria-valuenow="{{ $user->profileCompletionPercentage() }}"
               aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-9">
    <div class="row g-3">
      <div class="col-12">
        <div class="card hover-lift">
          <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.contact.title') }}</h6></div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6">
                <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i><strong>{{ __('profile.contact.phone') }}:</strong> {{ $user->phone ?? __('common.not_set') }}</p>
                <p class="mb-2"><i class="fas fa-birthday-cake me-2 text-primary"></i><strong>{{ __('profile.contact.birthdate') }}:</strong> {{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : __('common.not_set') }}</p>
              </div>
              <div class="col-sm-6">
                <p class="mb-2"><i class="fas fa-venus-mars me-2 text-primary"></i><strong>{{ __('profile.contact.gender') }}:</strong> {{ $user->gender === 'male' ? __('profile.gender.male') : __('profile.gender.female') }}</p>
                <p class="mb-0"><i class="fas fa-flag me-2 text-primary"></i><strong>{{ __('profile.contact.country') }}:</strong> {{ $user->location_text }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card hover-lift">
          <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.about.title') }}</h6></div>
          <div class="card-body">
            <p class="mb-0">{{ $user->about_me ?? __('profile.about.empty') }}</p>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-6">
        <div class="card hover-lift">
          <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.skills.title') }}</h6></div>
          <div class="card-body">
            @forelse($user->skills as $skill)
              <span class="badge skill-badge">{{ $skill->name_text ?? $skill->name }}</span>
            @empty
              <p class="text-muted mb-0">{{ __('profile.skills.empty') }}</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-6">
        <div class="card hover-lift">
          <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.languages.title') }}</h6></div>
          <div class="card-body">
            @forelse($user->languages as $language)
              <div class="d-flex justify-content-between border-bottom py-2">
                <strong>{{ $language->name }}</strong>
                <span class="lang-level">{{ $language->pivot->level ?? __('common.not_set') }}</span>
              </div>
            @empty
              <p class="text-muted mb-0">{{ __('profile.languages.empty') }}</p>
            @endforelse
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
