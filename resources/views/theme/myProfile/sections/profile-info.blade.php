<div class="tab-pane fade show active" id="profile-info">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <button class="btn btn-primary d-lg-none hover-lift" id="mobileSidebarToggle">
      <i class="fas fa-bars"></i> {{ __('profile.menu') }}
    </button>
  </div>

  <div class="row">
    <div class="col-lg-4 col-xl-3">
      <div class="card mb-4 hover-lift">
        <div class="card-body text-center">
          <img id="profileImage" src="{{ $user->image_url }}"
               class="rounded-circle profile-img mb-3" alt="{{ __('profile.image.alt') }}">
          <h3 class="fw-bold">{{ $user->fullName() }}</h3>
          <p class="text-muted mb-4">{{ $user->email }}</p>

          <input type="file" id="profileImageInput"
                 accept="image/jpeg, image/png, image/gif, image/webp"
                 style="display:none;">
          <div class="d-flex flex-column gap-2 mb-2">
            <button class="btn btn-primary hover-lift" id="uploadImageBtn">
              <i class="fas fa-camera me-1"></i> {{ __('profile.image.change') }}
            </button>

            @if ($user->image_path)
              <button class="btn btn-outline-danger hover-lift" id="removeImageBtn">
                <i class="fas fa-trash me-1"></i> {{ __('profile.image.remove') }}
              </button>
            @endif
          </div>
        </div>
      </div>

      {{-- Progress --}}
      <div class="card mb-4 hover-lift">
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

    <div class="col-lg-7 col-xl-6">
      {{-- Contact --}}
      <div class="card mb-4 hover-lift">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold">{{ __('profile.contact.title') }}</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p class="mb-3">
                <i class="fas fa-phone me-2 text-primary"></i>
                <strong>{{ __('profile.contact.phone') }}</strong>
                {{ $user->phone ?? __('common.not_set') }}
              </p>
              <p class="mb-3">
                <i class="fas fa-birthday-cake me-2 text-primary"></i>
                <strong>{{ __('profile.contact.birthdate') }}</strong>
                {{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : __('common.not_set') }}
              </p>
            </div>
            <div class="col-md-6">
              <p class="mb-3">
                <i class="fas fa-venus-mars me-2 text-primary"></i>
                <strong>{{ __('profile.contact.gender') }}:</strong>
                {{ $user->gender === 'male' ? __('profile.gender.male') : __('profile.gender.female') }}
              </p>
              <p class="mb-0">
                <i class="fas fa-flag me-2 text-primary"></i>
                <strong>{{ __('profile.contact.country') }}:</strong>
                {{ $user->country->name ?? __('common.not_set') }}
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- About --}}
      <div class="card mb-4 hover-lift">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold">{{ __('profile.about.title') }}</h6>
        </div>
        <div class="card-body">
          <p class="mb-0">{{ $user->about_me ?? __('profile.about.empty') }}</p>
        </div>
      </div>

      {{-- Skills --}}
      <div class="card mb-4 hover-lift">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold">{{ __('profile.skills.title') }}</h6>
        </div>
        <div class="card-body">
          @forelse($user->skills as $skill)
            <span class="badge skill-badge">{{ $skill->name }}</span>
          @empty
            <p class="text-muted mb-0">{{ __('profile.skills.empty') }}</p>
          @endforelse
        </div>
      </div>

      {{-- Languages --}}
      <div class="card mb-4 hover-lift">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold">{{ __('profile.languages.title') }}</h6>
        </div>
        <div class="card-body">
          @forelse($user->languages as $language)
            <div class="mb-2">
              <strong>{{ $language->name }}</strong>
              <span class="language-level">{{ $language->pivot->level ?? __('common.not_set') }}</span>
            </div>
          @empty
            <p class="text-muted mb-0">{{ __('profile.languages.empty') }}</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
