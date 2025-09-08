<form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mb-4">
  @csrf
  @method('PATCH')

  <div class="row g-3">
    <div class="col-12 col-xxl-8">
      {{-- Basic Info --}}
      <div class="card hover-lift">
        <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.basic.title') }}</h6></div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="first_name" class="form-label">{{ __('profile.basic.first_name') }}</label>
              <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            <div class="col-md-6">
              <label for="last_name" class="form-label">{{ __('profile.basic.last_name') }}</label>
              <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
            </div>
            <div class="col-12">
              <label for="email" class="form-label">{{ __('profile.basic.email') }}</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">{{ __('profile.basic.phone') }}</label>
              <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            
            <div class="col-md-6">
              <label for="date_of_birth" class="form-label">{{ __('profile.basic.birthdate') }}</label>
              <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6">
              <label for="gender" class="form-label">{{ __('profile.basic.gender') }}</label>
              <select class="form-select" id="gender" name="gender">
                <option value="">{{ __('profile.basic.choose') }}</option>
                <option value="male"   @selected(old('gender', $user->gender) == 'male')>{{ __('profile.gender.male') }}</option>
                <option value="female" @selected(old('gender', $user->gender) == 'female')>{{ __('profile.gender.female') }}</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="country_id" class="form-label">{{ __('profile.basic.country') }}</label>
              <select class="form-select" id="country_id" name="country_id">
                <option value="">{{ __('profile.basic.choose_country') }}</option>
                @foreach ($countries as $country)
                  <option value="{{ $country->id }}" @selected(old('country_id', $user->country_id) == $country->id)>{{ $country->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
  <div class="form-check mt-2">
    <input class="form-check-input" type="checkbox" id="is_mentor" name="is_mentor" value="1"
           {{ old('is_mentor', $user->is_mentor) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="is_mentor">
      {{ __('profile.i_am_mentor') ?? 'أنا Mentor / معلم' }}
    </label>
    <div class="form-text">
      {{ __('profile.i_am_mentor_help') ?? 'فعّلها لو بتقدّم تعليم/إرشاد.' }}
    </div>
  </div>
</div>
            <div class="col-12">
              <label for="about_me" class="form-label">{{ __('profile.basic.about_me') }}</label>
              <textarea class="form-control" id="about_me" name="about_me" rows="3">{{ old('about_me', $user->about_me) }}</textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- Skills --}}
      <div class="card hover-lift mt-3" id="skillsCard">
        <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.teachable_skills.title') }}</h6></div>
        <div class="card-body">
          <div class="row g-3 mb-2">
            <div class="col-md-4">
              <label for="skillFilterType" class="form-label">{{ __('profile.teachable_skills.filter_type') }}</label>
              <select class="form-select" id="skillFilterType">
                <option value="none">{{ __('profile.teachable_skills.filter_none') }}</option>
                <option value="skill">{{ __('profile.teachable_skills.filter_skill') }}</option>
                <option value="classification">{{ __('profile.teachable_skills.filter_classification') }}</option>
              </select>
            </div>
            <div class="col-md-4 d-none" id="skillNameFilterContainer">
              <label for="skillNameFilter" class="form-label">{{ __('profile.teachable_skills.search_by_skill') }}</label>
              <input type="text" class="form-control" id="skillNameFilter" placeholder="{{ __('profile.teachable_skills.search_by_skill_placeholder') }}">
            </div>
            <div class="col-md-4 d-none" id="classificationFilterContainer">
              <label for="classificationFilter" class="form-label">{{ __('profile.teachable_skills.search_by_classification') }}</label>
              <select class="form-select" id="classificationFilter">
                <option value="">{{ __('profile.teachable_skills.select_classification') }}</option>
                @foreach ($classifications as $classification)
                  <option value="{{ $classification->id }}">{{ $classification->name_text }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
             <thead class="table-light">
  <tr>
    <th width="30%">{{ __('profile.teachable_skills.table.skill') }}</th>
    <th width="30%">{{ __('profile.teachable_skills.table.classification') }}</th>
    <th width="15%" class="text-center">Level</th>
    <th width="15%">Description</th>
    <th width="10%" class="text-center">{{ __('profile.teachable_skills.table.select') }}</th>
  </tr>
</thead>

              <tbody id="skillsTableBody">
                {{-- يُرسم بالجافاسكربت --}}
              </tbody>
            </table>
            <input type="hidden" name="skills_data" id="skills_data">
          </div>

          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-2">
            <small class="text-muted">
              {{ __('profile.teachable_skills.pagination.showing', ['from'=>1, 'to'=>min(10, $skills->count()), 'total'=>$skills->count()]) }}
              <span id="skillsFrom" class="d-none">1</span><span class="d-none">-</span><span id="skillsTo" class="d-none">10</span>
              <span id="skillsTotal" class="d-none">{{ $skills->count() }}</span>
            </small>
            <nav aria-label="Skills pagination" class="mt-2 mt-sm-0">
              <ul id="skillsPagination" class="pagination pagination-sm mb-0">
                {{-- يُحدث بالجافاسكربت --}}
              </ul>
            </nav>
          </div>
        </div>
      </div>

      {{-- Languages --}}
      <div class="card hover-lift mt-3" id="languagesCard">
        <div class="card-header"><h6 class="m-0 fw-bold">{{ __('profile.languages_section.title') }}</h6></div>
        <div class="card-body">
          <div class="row g-3 mb-2">
            <div class="col-md-4">
              <label for="languageFilter" class="form-label">{{ __('profile.languages_section.filter') }}</label>
              <input type="text" class="form-control" id="languageFilter" placeholder="{{ __('profile.languages_section.filter.placeholder') }}">
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th width="50%">{{ __('profile.languages_section.table.language') }}</th>
                  <th width="30%">{{ __('profile.languages_section.table.level') }}</th>
                  <th width="20%" class="text-center">{{ __('profile.languages_section.table.select') }}</th>
                </tr>
              </thead>
              <tbody id="languagesTableBody">
                {{-- يُرسم بالجافاسكربت --}}
              </tbody>
            </table>
          </div>

          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-2">
            <small class="text-muted">
              {{ __('profile.languages_section.pagination.showing', ['from'=>1, 'to'=>min(10, $languages->count()), 'total'=>$languages->count()]) }}
              <span id="languagesFrom" class="d-none">1</span><span class="d-none">-</span><span id="languagesTo" class="d-none">10</span>
              <span id="languagesTotal" class="d-none">{{ $languages->count() }}</span>
            </small>
            <nav aria-label="Languages pagination" class="mt-2 mt-sm-0">
              <ul id="languagesPagination" class="pagination pagination-sm mb-0">
                {{-- يُحدث بالجافاسكربت --}}
              </ul>
            </nav>
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary btn-lg hover-lift">
          <i class="fas fa-save me-1"></i>{{ __('profile.btn.save_changes') }}
        </button>
      </div>
    </div>

    {{-- مساعد جانبي (اختياري) --}}
    <div class="col-12 col-xxl-4">
      <div class="alert alert-info h-100">
        <div class="d-flex align-items-center mb-2">
          <i class="bi bi-lightbulb me-2 fs-4"></i>
          <strong>{{ __('profile.tips.title') }}</strong>
        </div>
        <ul class="mb-0">
          <li>{{ __('profile.tips.photo') }}</li>
          <li>{{ __('profile.tips.about') }}</li>
          <li>{{ __('profile.tips.skills') }}</li>
        </ul>
      </div>
    </div>
  </div>
</form>
